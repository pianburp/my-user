<?php
$student = $student ?? [];
$errors = session('student_errors') ?? [];

$countries = [];
$countriesPath = APPPATH . 'Config/countries.json';
if (is_file($countriesPath)) {
    $countries = json_decode(file_get_contents($countriesPath), true) ?? [];
}
?>

<style>
.form-section {
    margin-bottom: 32px;
    background: var(--bg-primary);
    padding: 24px;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
}
.form-section-title {
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-color);
}
.form-section-title svg {
    width: 20px;
    height: 20px;
    color: var(--text-secondary);
}
.form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.form-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.form-group-grid {
    margin-bottom: 0;
}
.form-item-full {
    grid-column: 1 / -1;
}
.is-invalid {
    border-color: var(--danger-border) !important;
    background-color: #fffbfa !important;
    box-shadow: 0 0 0 1px var(--danger-border) inset !important;
}
.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.35rem;
    font-size: 0.8125rem;
    color: var(--danger-text);
    font-weight: 500;
}
@media (max-width: 768px) {
    .form-grid-2, .form-grid-3 {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="form-section">
    <h3 class="form-section-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Personal Information
    </h3>
    <div class="form-grid-3">
        <div class="form-group form-group-grid">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" 
                   class="form-input <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" 
                   required minlength="2" maxlength="100"
                   value="<?= esc(old('first_name', $student['first_name'] ?? '')) ?>">
            <?php if (isset($errors['first_name'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['first_name']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" 
                   class="form-input <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" 
                   required minlength="2" maxlength="100"
                   value="<?= esc(old('last_name', $student['last_name'] ?? '')) ?>">
            <?php if (isset($errors['last_name'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['last_name']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="gender" class="form-label">Gender</label>
            <select id="gender" name="gender" class="form-input <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" required>
                <?php $genderValue = old('gender', $student['gender'] ?? ''); ?>
                <option value="">Select Gender</option>
                <option value="male" <?= $genderValue === 'male' ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= $genderValue === 'female' ? 'selected' : '' ?>>Female</option>
                <option value="other" <?= $genderValue === 'other' ? 'selected' : '' ?>>Other</option>
            </select>
            <?php if (isset($errors['gender'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['gender']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input type="date" id="date_of_birth" name="date_of_birth" 
                   class="form-input <?= isset($errors['date_of_birth']) ? 'is-invalid' : '' ?>" required
                   value="<?= esc(old('date_of_birth', $student['date_of_birth'] ?? '')) ?>">
            <?php if (isset($errors['date_of_birth'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['date_of_birth']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" 
                   class="form-input <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                   required maxlength="150"
                   value="<?= esc(old('email', $student['email'] ?? '')) ?>">
            <?php if (isset($errors['email'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" 
                   class="form-input <?= isset($errors['phone_number']) ? 'is-invalid' : '' ?>" 
                   required maxlength="30"
                   value="<?= esc(old('phone_number', $student['phone_number'] ?? '')) ?>">
            <?php if (isset($errors['phone_number'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['phone_number']) ?></div>
            <?php endif ?>
        </div>
    </div>
</div>

<div class="form-section">
    <h3 class="form-section-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        Address Details
    </h3>
    <div class="form-grid-2">
        <div class="form-group form-group-grid form-item-full">
            <label for="address_line1" class="form-label">Address Line 1</label>
            <input type="text" id="address_line1" name="address_line1" 
                   class="form-input <?= isset($errors['address_line1']) ? 'is-invalid' : '' ?>" 
                   required maxlength="255"
                   value="<?= esc(old('address_line1', $student['address_line1'] ?? '')) ?>">
            <?php if (isset($errors['address_line1'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['address_line1']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid form-item-full">
            <label for="address_line2" class="form-label">Address Line 2 (Optional)</label>
            <input type="text" id="address_line2" name="address_line2" 
                   class="form-input <?= isset($errors['address_line2']) ? 'is-invalid' : '' ?>" 
                   maxlength="255"
                   value="<?= esc(old('address_line2', $student['address_line2'] ?? '')) ?>">
            <?php if (isset($errors['address_line2'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['address_line2']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="city" class="form-label">City</label>
            <input type="text" id="city" name="city" 
                   class="form-input <?= isset($errors['city']) ? 'is-invalid' : '' ?>" 
                   required maxlength="100"
                   value="<?= esc(old('city', $student['city'] ?? '')) ?>">
            <?php if (isset($errors['city'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['city']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="state" class="form-label">State / Province</label>
            <input type="text" id="state" name="state" 
                   class="form-input <?= isset($errors['state']) ? 'is-invalid' : '' ?>" 
                   required maxlength="100"
                   value="<?= esc(old('state', $student['state'] ?? '')) ?>">
            <?php if (isset($errors['state'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['state']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="postcode" class="form-label">Zip / Postcode</label>
            <input type="text" id="postcode" name="postcode" 
                   class="form-input <?= isset($errors['postcode']) ? 'is-invalid' : '' ?>" 
                   required maxlength="20"
                   value="<?= esc(old('postcode', $student['postcode'] ?? '')) ?>">
            <?php if (isset($errors['postcode'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['postcode']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="country" class="form-label">Country / Region</label>
            <?php $countryValue = old('country', $student['country'] ?? 'Malaysia'); ?>
            <select id="country" name="country" class="form-input <?= isset($errors['country']) ? 'is-invalid' : '' ?>" required>
                <option value="">Select Country</option>
                <?php foreach ($countries as $c) : ?>
                    <option value="<?= esc($c) ?>" <?= $countryValue === $c ? 'selected' : '' ?>>
                        <?= esc($c) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['country'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['country']) ?></div>
            <?php endif ?>
        </div>
    </div>
</div>

<div class="form-section">
    <h3 class="form-section-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm-4 6v-7.5l4-2.222" />
        </svg>
        Enrollment Details
    </h3>
    <div class="form-grid-2">
        <div class="form-group form-group-grid">
            <label for="program_id" class="form-label">Target Program</label>
            <?php $selectedProgram = (int) old('program_id', $student['program_id'] ?? 0); ?>
            <select id="program_id" name="program_id" class="form-input <?= isset($errors['program_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">Select Program</option>
                <?php foreach ($programs as $program) : ?>
                    <option value="<?= (int) $program['id'] ?>" <?= $selectedProgram === (int) $program['id'] ? 'selected' : '' ?>>
                        <?= esc($program['program_name'] . ' (' . $program['program_code'] . ')') ?>
                    </option>
                <?php endforeach ?>
            </select>
            <?php if (isset($errors['program_id'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['program_id']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="intake_session" class="form-label">Intake Session (YYYY-MM)</label>
            <input type="text" id="intake_session" name="intake_session" 
                   class="form-input <?= isset($errors['intake_session']) ? 'is-invalid' : '' ?>" 
                   required pattern="\d{4}-(0[1-9]|1[0-2])" title="Format: YYYY-MM (e.g. 2026-01)"
                   placeholder="2026-01" value="<?= esc(old('intake_session', $student['intake_session'] ?? '')) ?>">
            <?php if (isset($errors['intake_session'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['intake_session']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="enrollment_date" class="form-label">Enrollment Date</label>
            <input type="date" id="enrollment_date" name="enrollment_date" 
                   class="form-input <?= isset($errors['enrollment_date']) ? 'is-invalid' : '' ?>" required
                   value="<?= esc(old('enrollment_date', $student['enrollment_date'] ?? date('Y-m-d'))) ?>">
            <?php if (isset($errors['enrollment_date'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['enrollment_date']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="status" class="form-label">Record Status</label>
            <?php $status = old('status', $student['status'] ?? 'active'); ?>
            <select id="status" name="status" class="form-input <?= isset($errors['status']) ? 'is-invalid' : '' ?>">
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                <option value="graduated" <?= $status === 'graduated' ? 'selected' : '' ?>>Graduated</option>
                <option value="suspended" <?= $status === 'suspended' ? 'selected' : '' ?>>Suspended</option>
            </select>
            <?php if (isset($errors['status'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['status']) ?></div>
            <?php endif ?>
        </div>
    </div>
</div>

<div class="form-section" style="margin-bottom: 20px;">
    <h3 class="form-section-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
        </svg>
        Supporting Document (Optional)
    </h3>
    <div class="form-grid-2">
        <div class="form-group form-group-grid">
            <label for="document_type" class="form-label">Document Type</label>
            <?php $documentType = old('document_type', ''); ?>
            <select id="document_type" name="document_type" class="form-input <?= isset($errors['document_type']) ? 'is-invalid' : '' ?>">
                <option value="">Select Document Type</option>
                <option value="ic" <?= $documentType === 'ic' ? 'selected' : '' ?>>IC / National ID</option>
                <option value="passport" <?= $documentType === 'passport' ? 'selected' : '' ?>>Passport</option>
                <option value="transcript" <?= $documentType === 'transcript' ? 'selected' : '' ?>>Transcript</option>
                <option value="certificate" <?= $documentType === 'certificate' ? 'selected' : '' ?>>Certificate</option>
            </select>
            <?php if (isset($errors['document_type'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['document_type']) ?></div>
            <?php endif ?>
        </div>

        <div class="form-group form-group-grid">
            <label for="document_file" class="form-label">Upload File (PDF/JPG/PNG, max 2MB)</label>
            <input type="file" id="document_file" name="document_file" 
                   class="form-input <?= isset($errors['document_file']) ? 'is-invalid' : '' ?>" 
                   accept=".pdf,.jpg,.jpeg,.png">
            <?php if (isset($errors['document_file'])) : ?>
                <div class="invalid-feedback"><?= esc($errors['document_file']) ?></div>
            <?php endif ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone_number');
    const intakeInput = document.getElementById('intake_session');
    const postcodeInput = document.getElementById('postcode');

    // Dynamic Validation for Postcode
    if (postcodeInput) {
        postcodeInput.addEventListener('input', function(e) {
            // Remove alphabets (and keep numbers, spaces, or hyphens like 12345-6789)
            this.value = this.value.replace(/[^0-9\-\s]/g, '');
        });
    }

    // Dynamic Formatting for Malaysia Phone Number (+60 format)
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let digits = this.value.replace(/\D/g, '');
            if (digits === '') {
                this.value = '';
                return;
            }

            // Normalize leading zero or bare numbers to '60'
            if (digits.startsWith('0')) {
                digits = '60' + digits.substring(1);
            } else if (digits.length > 0 && !digits.startsWith('6')) {
                digits = '60' + digits;
            }

            let formatted = '';
            if (digits.length <= 2) {
                formatted = '+' + digits;
            } else {
                formatted = '+' + digits.substring(0, 2) + ' '; // e.g., +60
                let rest = digits.substring(2);
                
                let prefixLen = rest.startsWith('1') ? 2 : 1; 
                
                if (rest.length <= prefixLen) {
                    formatted += rest;
                } else {
                    formatted += rest.substring(0, prefixLen) + '-';
                    rest = rest.substring(prefixLen);
                    
                    if (rest.length > 3) {
                         if (rest.length >= 8) {
                              formatted += rest.substring(0, 4) + ' ' + rest.substring(4, 8); 
                         } else {
                              formatted += rest.substring(0, 3) + ' ' + rest.substring(3, 7);
                         }
                    } else {
                        formatted += rest;
                    }
                }
            }
            this.value = formatted;
        });
    }

    // Dynamic Masking for Intake Session (YYYY-MM)
    if (intakeInput) {
        intakeInput.addEventListener('input', function(e) {
            // Remove everything except numbers
            let val = this.value.replace(/\D/g, '');
            
            // Limit to 6 digits maximum
            val = val.substring(0, 6);
            
            // Format as YYYY-MM
            if (val.length > 4) {
                val = val.substring(0, 4) + '-' + val.substring(4, 6);
            }
            this.value = val;
        });
        
        // Handle backspace gracefully for the hyphen
        intakeInput.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value.length === 6 && this.value.includes('-')) {
                // If user is hitting backspace right after the hyphen, remove the hyphen and the number before it
                this.value = this.value.substring(0, 4);
                e.preventDefault();
            }
        });
    }
});
</script>
