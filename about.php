<?php
require_once __DIR__ . '/includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>About - LabGuard</title>
	<link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<style>
        body { background-color: #f8f9fa; }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .student-header {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            border: none;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .priority-high { color: #e74c3c; }
        .priority-medium { color: #f39c12; }
        .priority-low { color: #27ae60; }
    </style>
<body style="background-color: #edf5f2ff;">
	<?php render_navbar(); ?>
	<div class="container">
		<div class="card">
			<h3 style="margin-top:0;"><i class="fas fa-lightbulb"></i>  About LabGuard :-</h3><hr>
			<p class="help" style="margin-top:6px;">LabGuard helps students report lab equipment issues and enables admins to verify and resolve them efficiently.</p>
		</div>
		<div class="grid">
			<div class="card">
				<h4 style="margin:0 0 8px;"><i class="fas fa-user-graduate"></i>  For Students :-</h4><hr>
				<ul style="margin:0; padding-left:18px; color:var(--primary-700);">
					<li>Simple login and quick problem reporting</li>
					<li>Attach photos for clarity</li>
					<li>Track status: Pending, Verified, Solved</li>
				</ul>
			</div>
			<div class="card">
				<h4 style="margin:0 0 8px;"><i class="fas fa-user-cog"></i>  For Admins :-</h4><hr>
				<ul style="margin:0; padding-left:18px; color:var(--primary-700);">
					<li>Dashboard with key stats</li>
					<li>Verify issues and mark as Solved</li>
					<li>View all reported problems with details</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- Guidelines -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0"><i class="fas fa-info-circle me-2"></i>      Reporting Guidelines :   </h2>
                    </div>
                    <div class="card-body">
                        <h4><i class="fas fa-exclamation-circle"></i>  Priority Levels :-</h4>
                        <ul class="list-unstyled" style="list-style-type: none;">
                            <li ><i  class="fas fa-circle text-danger me-2" style="color: red;"></i> <strong >High:</strong> Safety issues, equipment damage</li>
                            <li><i class="fas fa-circle text-warning me-2" style="color: yellow;"></i> <strong >Medium:</strong> Functional problems, minor damage</li>
                            <li><i class="fas fa-circle text-success me-2" style="color: green;"></i> <strong >Low:</strong> Minor issues, suggestions</li>
                        </ul>
                        
                        <hr>
                        
                        <h4><i class="fas fa-list-alt"></i>  What to Include :-</h4>
                        <ul>
                            <li>Specific location of the issue</li>
                            <li>Equipment name/model (if applicable)</li>
                            <li>Detailed description of the problem</li>
                            <li>When the issue occurred</li>
                            <li>Any error messages seen</li>
                        </ul>
                        
                        <hr>
                        
                        <h4><i class="fas fa-clock"></i>  Response Time :-</h4>
                        <ul class="list-unstyled" >
                            <li ><strong style="color: #e74c3c;">High Priority:</strong> Within 2 hours</li>
                            <li><strong style=" color: #f0ac19ff;">Medium Priority:</strong> Within 24 hours</li>
                            <li><strong style="color: #27ae60;">Low Priority:</strong> Within 3 days</li>
                        </ul>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0"><i class="fas fa-phone me-2"></i> Emergency Contact :</h2>
                    </div>
                    <div class="card-body">
                        <p><strong><i class="fas fa-bell"></i>  For urgent safety issues :-</strong></p>
                        <p><i class="fas fa-phone me-2"></i> Emergency: 911</p>
                        <p><i class="fas fa-phone me-2"></i> Lab Security: (555) 123-4567</p>
                        <p><i class="fas fa-envelope me-2"></i> Email: security@labguard.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>


