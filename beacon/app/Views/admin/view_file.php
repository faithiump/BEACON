<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View File - <?= esc($file['file_name']) ?> - BEACON Admin</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f1f5f9;
            overflow: hidden;
        }

        .file-viewer-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .file-viewer-header {
            background: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .file-viewer-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.2s;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .back-button:hover {
            background: rgba(102, 126, 234, 0.1);
            gap: 0.75rem;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .file-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .file-details h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .file-details p {
            font-size: 0.8125rem;
            color: #64748b;
        }

        .file-viewer-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-download {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .btn-download:hover {
            background: rgba(102, 126, 234, 0.15);
            transform: translateY(-1px);
        }

        .file-viewer-content {
            flex: 1;
            overflow: hidden;
            background: #f8fafc;
            position: relative;
        }

        .file-viewer-iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: white;
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e2e8f0;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            color: #64748b;
            font-size: 0.875rem;
        }

        .error-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .error-message i {
            font-size: 3rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }

        .error-message h3 {
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .error-message p {
            color: #64748b;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .file-viewer-header {
                padding: 0.75rem 1rem;
            }

            .file-viewer-header-left {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .file-viewer-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="file-viewer-container">
        <div class="file-viewer-header">
            <div class="file-viewer-header-left">
                <a href="javascript:history.back()" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
                <div class="file-info">
                    <div class="file-icon">
                        <i class="fas fa-file-<?= $file['file_type'] === 'constitution' ? 'contract' : 'certificate' ?>"></i>
                    </div>
                    <div class="file-details">
                        <h3><?= esc($file['file_name']) ?></h3>
                        <p><?= ucfirst(str_replace('_', ' ', $file['file_type'])) ?> • <?= $file['file_size'] ? number_format($file['file_size'] / 1024, 2) . ' KB' : 'Unknown size' ?></p>
                    </div>
                </div>
            </div>
            <div class="file-viewer-actions">
                <a href="<?= base_url('admin/organizations/file/' . $file['id'] . '/download') ?>" target="_blank" class="btn-action btn-download">
                    <i class="fas fa-download"></i>
                    Download
                </a>
            </div>
        </div>
        <div class="file-viewer-content">
            <?php if ($extension === 'pdf'): ?>
                <iframe src="<?= base_url('admin/organizations/file/' . $file['id']) ?>" class="file-viewer-iframe" id="fileViewer" type="application/pdf"></iframe>
            <?php else: ?>
                <!-- For DOC/DOCX and other files, try to display in iframe -->
                <!-- Browser may download if it can't display, which is acceptable -->
                <iframe src="<?= esc($fileUrl) ?>" class="file-viewer-iframe" id="fileViewer"></iframe>
            <?php endif; ?>
            <div class="loading-spinner" id="loadingSpinner">
                <div class="spinner"></div>
                <div class="loading-text">Loading file...</div>
            </div>
            <div class="error-message" id="errorMessage" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Unable to display file</h3>
                <p>The file cannot be displayed in the browser. Please download it to view.</p>
                <a href="<?= base_url('admin/organizations/file/' . $file['id'] . '/download') ?>" target="_blank" class="btn-action btn-download">
                    <i class="fas fa-download"></i>
                    Download File
                </a>
            </div>
        </div>
    </div>

    <script>
        const iframe = document.getElementById('fileViewer');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const errorMessage = document.getElementById('errorMessage');

        // Hide loading spinner when iframe loads
        iframe.addEventListener('load', function() {
            loadingSpinner.style.display = 'none';
        });

        // Show error message if iframe fails to load
        iframe.addEventListener('error', function() {
            loadingSpinner.style.display = 'none';
            errorMessage.style.display = 'block';
        });

        // Timeout fallback - if file doesn't load in 10 seconds, show error
        setTimeout(function() {
            if (loadingSpinner.style.display !== 'none') {
                loadingSpinner.style.display = 'none';
                errorMessage.style.display = 'block';
            }
        }, 10000);
    </script>
</body>
</html>

