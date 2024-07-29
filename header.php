        <div class="sidebar-left">
            <!--responsive view logo start-->
            <div class="logo dark-logo-bg visible-xs-* visible-sm-*" style="background:#90c549;">
                <a href="portal.php">
                    <img style="width: 20px;" src="views/img/lgsm.png" alt="">
                    <!--<i class="fa fa-maxcdn"></i>-->
                    <span class="brand-name">RAW</span>
                </a>
            </div>
            <!--responsive view logo end-->
            <div class="sidebar-left-info">
                <!-- visible small devices start-->
                <div class=" search-field">  </div>
                <!-- visible small devices end-->
                <!--sidebar nav start-->
                <ul class="nav nav-pills nav-stacked side-navigation">
                    <li>
                        <h3 class="navigation-title">Navigation</h3>
                    </li>
					 <li class="active"><a href="home.php"><i class="fa fa-home"></i> <span>Encrypt Data </span></a></li>
					 <li class="active"><a href="details.php"><i class="fa fa-home"></i> <span>Decrypt Data </span></a></li>
                    </ul>
                </div>
                <!--sidebar widget end-->

            </div>
        </div>
        <!-- sidebar left end-->

        <!-- body content start-->
        <div class="body-content" >

            <!-- header section start-->
            <div style="background:#90c549;" class="header-section">
                <!--logo and logo icon start-->
                <div style="background:#90c549;" class="logo dark-logo-bg hidden-xs hidden-sm">
                    <a href="portal.php">
                        <img style="width: 20px;" src="views/img/lgsm.png" alt="">
                        <!--<i class="fa fa-maxcdn"></i>-->
                        <span class="brand-name">RAW</span>
                    </a>
                </div>
                <div class="icon-logo dark-logo-bg hidden-xs hidden-sm">
                    <a href="portal.php">
                        <img style="width: 20px;" src="views/img/lgsm.png" alt="">
                        <!--<i class="fa fa-maxcdn"></i>-->
                    </a>
                </div>
                <!--logo and logo icon end-->

                <!--toggle button start-->
                <a class="toggle-btn"><i class="fa fa-outdent"></i></a>
                <!--toggle button end-->

                <!--mega menu start-->
                
                <!--mega menu end-->
                <div class="notification-wrap">
                <!--left notification start-->
               <div class="left-notification">
                <ul class="notification-menu">
                <!--mail info start-->
                
                <!--mail info end-->

                <!--task info start-->
                
                <!--task info end-->

                <!--notification info start-->
                
                <!--notification info end-->
                </ul>
                </div>
                <!--left notification end-->


                <!--right notification start-->
                <div class="right-notification">
                    <ul class="notification-menu">
                        <li>
                            <a href="javascript:;" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <img src="views/img/avatar-mini.jpg" alt=""><b style="color: #00304e;"><?php echo $userRow['user_name'] ; ?></b>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu purple pull-right">
                                <li><a href="javascript:;">  Profile</a></li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-danger pull-right">40%</span>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="label bg-info pull-right">new</span>
                                        Help
                                    </a>
                                </li>
                                <li><a href="logout.php?logout=true"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                            </ul>
                        </li>


                    </ul>
                </div>
                <!--right notification end-->
                </div>

            </div>
			
            <!-- header section end-->