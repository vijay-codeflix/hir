<head>
    <meta charset="utf-8">

    <title>ProUI - Responsive Bootstrap Admin Template</title>

    <meta name="description" content="ProUI is a Responsive Bootstrap Admin Template created by pixelcave and published on Themeforest.">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <!--<link rel="shortcut icon" href="img/favicon.png">-->
    <!--<link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">-->
    <!--<link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">-->
    <!--<link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">-->
    <!--<link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">-->
    <!--<link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">-->
    <!--<link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">-->
    <!--<link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">-->
    <!--<link rel="apple-touch-icon" href="img/icon180.png" sizes="180x180">-->
    <!-- END Icons -->

    <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>bootstrap.min.css">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>fonts.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>main.css">

    <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->

    <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
    <link rel="stylesheet" href="https://codeflixweb.com/staff-monitor/assets/admin/css/bootstrap.min.css">
    <!-- END Stylesheets -->

    <!-- Modernizr (browser feature detection library) & Respond.js (enables responsive CSS code on browsers that don't support it, eg IE8) -->
    <script src="<?php echo ASSETS_JS; ?>vendor/modernizr-respond.min.js"></script>
</head>

<body>
    
    <div class="container">
        
        <div class="page-content">
                
        <!-- Progress Bar Wizard Block -->
                <div class="block">
                    <!-- Progress Bars Wizard Title -->
                        <div class="block-title text-center">
                            <h2><strong>Company</strong> Regestration</h2>
                        </div>
                    <!-- END Progress Bar Wizard Title -->

                    <!-- Progress Bar Wizard Content -->
                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-1">
                            <div class="block-section">
                                <h3 class="sub-header text-center"><strong>Sign Up with 3 easy steps!</strong></h3>
                                <p class="clearfix"><i class="fa fa-plus fa-5x text-primary pull-left"></i>Sign up today and receive <span class="text-success"><strong>30% discount</strong></span> on all plans! Our web application will save you time and enable you to work faster and more efficiently.</p>
                                <p>
                                    <a href="javascript:void(0)" class="btn btn-lg btn-primary btn-block">Learn More.. <i class="fa fa-arrow-right"></i></a>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-sm-offset-1">
                            <!-- Wizard Progress Bar, functionality initialized in js/pages/formsWizard.js -->
                            <div class="progress progress-striped active">
                                <div id="progress-bar-wizard" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>
                            </div>
                            <!-- END Wizard Progress Bar -->

                            <!-- Progress Wizard Content -->
                            <form id="progress-wizard" action="page_forms_wizard.html" method="post" class="form-horizontal">
                                <!-- First Step -->
                                <div id="progress-first" class="step">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-username">Username</label>
                                        <div class="col-md-8">
                                            <input type="text" id="example-progress-username" name="example-progress-username" class="form-control" placeholder="Your username..">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-email">Email</label>
                                        <div class="col-md-8">
                                            <input type="text" id="example-progress-email" name="example-progress-email" class="form-control" placeholder="test@example.com">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-password">Password</label>
                                        <div class="col-md-8">
                                            <input type="password" id="example-progress-password" name="example-progress-password" class="form-control" placeholder="Choose a crazy one..">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-password2">Retype Password</label>
                                        <div class="col-md-8">
                                            <input type="password" id="example-progress-password2" name="example-progress-password2" class="form-control" placeholder="..and confirm it!">
                                        </div>
                                    </div>
                                </div>
                                <!-- END First Step -->

                                <!-- Second Step -->
                                <div id="progress-second" class="step">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-firstname">Firstname</label>
                                        <div class="col-md-8">
                                            <input type="text" id="example-progress-firstname" name="example-progress-firstname" class="form-control" placeholder="John..">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-lastname">Lastname</label>
                                        <div class="col-md-8">
                                            <input type="text" id="example-progress-lastname" name="example-progress-lastname" class="form-control" placeholder="Doe..">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-address">Address</label>
                                        <div class="col-md-8">
                                            <input type="text" id="example-progress-address" name="example-progress-address" class="form-control" placeholder="Where do you live?">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-city">City</label>
                                        <div class="col-md-8">
                                            <input type="text" id="example-progress-city" name="example-progress-city" class="form-control" placeholder="Which one?">
                                        </div>
                                    </div>
                                </div>
                                <!-- END Second Step -->

                                <!-- Third Step -->
                                <div id="progress-third" class="step">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-bio">Bio</label>
                                        <div class="col-md-8">
                                            <textarea id="example-progress-bio" name="example-progress-bio" rows="5" class="form-control" placeholder="Tell us your story.."></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-newsletter">Newsletter</label>
                                        <div class="col-md-8">
                                            <div class="checkbox">
                                                <label for="example-progress-newsletter">
                                                    <input type="checkbox" id="example-progress-newsletter" name="example-progress-newsletter"> Sign up
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><a href="#modal-terms" data-toggle="modal">Terms</a></label>
                                        <div class="col-md-8">
                                            <label class="switch switch-primary" for="example-progress-terms">
                                                <input type="checkbox" id="example-progress-terms" name="example-progress-terms" value="1">
                                                <span data-toggle="tooltip" title="I agree to the terms!"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Third Step -->

                                <!-- Form Buttons -->
                                <div class="form-group form-actions">
                                    <div class="col-md-8 col-md-offset-4">
                                        <input type="reset" class="btn btn-sm btn-warning" id="back3" value="Back">
                                        <input type="submit" class="btn btn-sm btn-primary" id="next3" value="Next">
                                    </div>
                                </div>
                                <!-- END Form Buttons -->
                            </form>
                            <!-- END Progress Wizard Content -->
                        </div>
                    </div>
                    <!-- END Progress Bar Wizard Content -->
                </div>
                <!-- END Progress Bar Wizard Block -->
                 
        </div>
        
    </div>

<!-- Bootstrap.js, Jquery plugins and Custom JS code -->
<script src="https://codeflixweb.com/staff/assets/admin/js/vendor/jquery-1.11.2.min.js"></script>
<script src="https://codeflixweb.com/staff/assets/admin/js/vendor/bootstrap.min.js"></script>
<script src="https://codeflixweb.com/staff/assets/admin/js/plugins.js"></script>
<script src="https://codeflixweb.com/staff/assets/admin/js/app.js"></script>

<!-- Load and execute javascript code used only in this page -->
<script src="https://codeflixweb.com/staff-monitor/assets/admin/js/pages/formsWizard.js"></script>
<script>$(function(){ FormsWizard.init(); });</script>
</body>