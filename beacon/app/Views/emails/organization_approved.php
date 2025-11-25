<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .success-badge { background: #22c55e; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block; margin: 20px 0; }
        .info-box { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #667eea; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BEACON CSPC</h1>
            <p>Organization Application Approved</p>
        </div>
        <div class="content">
            <div class="success-badge">✓ APPROVED</div>
            
            <h2>Congratulations!</h2>
            <p>We are pleased to inform you that your organization application for <strong><?= esc($application['organization_name']) ?></strong> has been <strong>approved</strong> by the administration.</p>
            
            <div class="info-box">
                <h3>Organization Details:</h3>
                <p><strong>Name:</strong> <?= esc($application['organization_name']) ?> (<?= esc($application['organization_acronym']) ?>)</p>
                <p><strong>Type:</strong> <?= esc(ucfirst(str_replace('_', ' ', $application['organization_type']))) ?></p>
                <p><strong>Category:</strong> <?= esc(ucfirst(str_replace('_', ' ', $application['organization_category']))) ?></p>
                <p><strong>Contact Email:</strong> <?= esc($application['contact_email']) ?></p>
            </div>
            
            <?php if (isset($officer)): ?>
            <div class="info-box">
                <h3>Primary Officer:</h3>
                <p><strong>Name:</strong> <?= esc($officer['name']) ?></p>
                <p><strong>Position:</strong> <?= esc($officer['position']) ?></p>
                <p><strong>Email:</strong> <?= esc($officer['email']) ?></p>
            </div>
            <?php endif; ?>
            
            <div class="info-box">
                <h3>Next Steps:</h3>
                <p>1. Your organization account has been created in the BEACON system.</p>
                <p>2. You will receive separate login credentials via email.</p>
                <p>3. Please log in and complete your organization profile.</p>
                <p>4. You can now start creating events and managing your organization.</p>
            </div>
            
            <p>If you have any questions, please contact the administration.</p>
            
            <div class="footer">
                <p>This is an automated message from BEACON CSPC.</p>
                <p>Camarines Sur Polytechnic Colleges</p>
            </div>
        </div>
    </div>
</body>
</html>

