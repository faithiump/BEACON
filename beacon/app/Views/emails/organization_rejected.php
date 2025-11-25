<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .notice-badge { background: #f59e0b; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block; margin: 20px 0; }
        .info-box { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #f59e0b; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BEACON CSPC</h1>
            <p>Organization Application Status</p>
        </div>
        <div class="content">
            <div class="notice-badge">Application Status Update</div>
            
            <h2>Application Review Complete</h2>
            <p>We regret to inform you that your organization application for <strong><?= esc($application['organization_name']) ?></strong> has been <strong>rejected</strong> by the administration.</p>
            
            <div class="info-box">
                <h3>Organization Details:</h3>
                <p><strong>Name:</strong> <?= esc($application['organization_name']) ?> (<?= esc($application['organization_acronym']) ?>)</p>
                <p><strong>Type:</strong> <?= esc(ucfirst(str_replace('_', ' ', $application['organization_type']))) ?></p>
                <p><strong>Category:</strong> <?= esc(ucfirst(str_replace('_', ' ', $application['organization_category']))) ?></p>
            </div>
            
            <?php if (!empty($application['admin_notes'])): ?>
                <div class="info-box">
                    <h3>Review Notes:</h3>
                    <p><?= nl2br(esc($application['admin_notes'])) ?></p>
                </div>
            <?php endif; ?>

            <div class="info-box">
                <h3>Next Steps:</h3>
                <p>If you believe this decision was made in error or would like to discuss your application further, please contact the administration office.</p>
                <p>You may also submit a new application with updated information if needed.</p>
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

