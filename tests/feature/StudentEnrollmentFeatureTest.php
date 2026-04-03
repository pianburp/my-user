<?php

use App\Database\Seeds\ProgramSeeder;
use App\Models\ProgramModel;
use App\Models\StudentDocumentModel;
use App\Models\StudentModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class StudentEnrollmentFeatureTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $namespace = null;
    protected $seed      = ProgramSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        // Routes are redefined in tests to focus on student module behavior, not auth filters.
        $this->withRoutes([
            ['GET', 'user/students', 'StudentController::index'],
            ['GET', 'user/students/create', 'StudentController::create'],
            ['POST', 'user/students/store', 'StudentController::store'],
            ['GET', 'user/students/(:num)/edit', 'StudentController::edit/$1'],
            ['POST', 'user/students/(:num)/update', 'StudentController::update/$1'],
            ['POST', 'user/students/(:num)/delete', 'StudentController::delete/$1'],
        ]);
    }

    public function testCreateFlowCreatesStudentWithGeneratedId(): void
    {
        $payload = $this->validPayload([
            'email' => 'create.flow@example.test',
        ]);

        $response = $this->post('/user/students/store', $payload);

        $response->assertRedirectTo('/user/students');

        $student = (new StudentModel())->where('email', 'create.flow@example.test')->first();

        $this->assertNotNull($student);
        $this->assertSame('active', $student['status']);
        $this->assertMatchesRegularExpression('/^STU\d{8}$/', $student['student_id']);
    }

    public function testSearchFlowFindsByNameStudentIdAndEmail(): void
    {
        $service = service('studentService');

        $firstId = $service->createStudent($this->validPayload([
            'first_name' => 'Alice',
            'last_name'  => 'Carter',
            'email'      => 'alice.search@example.test',
        ]));

        $service->createStudent($this->validPayload([
            'first_name' => 'Brian',
            'last_name'  => 'Ng',
            'email'      => 'brian.search@example.test',
        ]));

        $firstStudent = (new StudentModel())->find($firstId);
        $this->assertIsArray($firstStudent);

        $nameResponse = $this->get('/user/students?q=Alice');
        $nameResponse->assertStatus(200);
        $nameResponse->assertSee('Alice Carter');
        $nameResponse->assertDontSee('Brian Ng');

        $idResponse = $this->get('/user/students?q=' . $firstStudent['student_id']);
        $idResponse->assertStatus(200);
        $idResponse->assertSee($firstStudent['student_id']);

        $emailResponse = $this->get('/user/students?q=alice.search@example.test');
        $emailResponse->assertStatus(200);
        $emailResponse->assertSee('alice.search@example.test');
    }

    public function testUploadFlowStoresDocumentMetadata(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'stu_pdf_');
        $this->assertNotFalse($tempFile);
        file_put_contents($tempFile, "%PDF-1.4\nstudent test document\n");

        $fileSize = filesize($tempFile);
        $this->assertNotFalse($fileSize);

        $uploadedFile = $this->getMockBuilder(UploadedFile::class)
            ->setConstructorArgs([$tempFile, 'id-proof.pdf', 'application/pdf', (int) $fileSize, UPLOAD_ERR_OK, null])
            ->onlyMethods(['isValid', 'getError', 'getExtension', 'getSize', 'getTempName', 'getRandomName', 'move'])
            ->getMock();

        $uploadedFile->method('isValid')->willReturn(true);
        $uploadedFile->method('getError')->willReturn(UPLOAD_ERR_OK);
        $uploadedFile->method('getExtension')->willReturn('pdf');
        $uploadedFile->method('getSize')->willReturn((int) $fileSize);
        $uploadedFile->method('getTempName')->willReturn($tempFile);
        $uploadedFile->method('getRandomName')->willReturn('id-proof.pdf');
        $uploadedFile->method('move')->willReturnCallback(static function (string $destination, ?string $name = null): bool {
            $name ??= 'id-proof.pdf';

            if (! is_dir($destination)) {
                mkdir($destination, 0775, true);
            }

            return (bool) file_put_contents(rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name, 'pdf-content');
        });

        $studentId = service('studentService')->createStudent(
            $this->validPayload(['email' => 'upload.flow@example.test']),
            $uploadedFile,
            'passport'
        );

        $documents = (new StudentDocumentModel())->findByStudent($studentId);

        $this->assertCount(1, $documents);
        $this->assertSame('passport', $documents[0]['document_type']);
        $this->assertStringContainsString('uploads/students/STU', $documents[0]['file_path']);

        @unlink($tempFile);
    }

    private function validPayload(array $overrides = []): array
    {
        $program = (new ProgramModel())->first();
        $this->assertIsArray($program);

        $payload = [
            'first_name'      => 'John',
            'last_name'       => 'Doe',
            'gender'          => 'male',
            'date_of_birth'   => '2004-05-20',
            'email'           => 'john.doe@example.test',
            'phone_number'    => '+60111222333',
            'address_line1'   => '123 Main Street',
            'address_line2'   => 'Unit 10A',
            'city'            => 'Kuala Lumpur',
            'state'           => 'Wilayah Persekutuan',
            'postcode'        => '50000',
            'country'         => 'Malaysia',
            'program_id'      => (int) $program['id'],
            'intake_session'  => '2026-01',
            'enrollment_date' => '2026-01-15',
            'status'          => 'active',
        ];

        return array_merge($payload, $overrides);
    }
}
