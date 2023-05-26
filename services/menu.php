 <aside class="main-sidebar sidebar-dark-primary elevation-4" id="mymainsidebar">
            <a href="#" class="brand-link logo-switch">
                <img src="img/agroferias_logo2.png" alt="AGROFERIAS" class="logo-xl">
            </a>


            <!-- Sidebar -->
            <div class="sidebar">
			
<!-- Sidebar Menu -->
                <nav class="mt-2">
				    
                    <ul class="nav nav-sidebar flex-column" role="menu" data-accordion="false">
								<?php if( $_SESSION['level'] >= 500 ){ ?>
								
								<li class="nav-item item">
                                    <a href="ventas.php" class="nav-link <?php echo $active['ventasdia']; ?>">
                                        <i class="fa  fa-shopping-cart  nav-icon"></i>
										<p>Ventas Día</p>
                                    </a>
                                </li>
								
								<li class="nav-item item">
                                    <a href="fallidos.php?dia=hoy" class="nav-link <?php echo $active['ifodia']; ?>">
                                        <i class="fa  fa-ban  nav-icon"></i>
										<p>Intentos Fallidos del Día</p>
                                    </a>
                                </li>
								
								<li class="nav-item item">
                                    <a href="reportes.php" class="nav-link <?php echo $active['report']; ?>">
                                        <i class="fa fa-cart-plus nav-icon"></i>
										<p>Ventas Total</p>
                                    </a>
                                </li>
								
								<li class="nav-item item">
                                    <a href="fallidos.php" class="nav-link <?php echo $active['ifototal']; ?>">
                                        <i class="fa fa-ban  nav-icon"></i>
										<p>Intentos Fallidos Total</p>
                                    </a>
                                </li>
								
								
								
								<?php /* ?> 
								<li class="nav-item item">
                                    <a href="mapa.php" class="nav-link <?php echo $active['map']; ?>">
                                        <i class="fa fa-map nav-icon"></i>
										<p>Mapa</p>
                                    </a>
                                </li>
								<?php */ ?>
								
								
								<?php } ?>
								
								<?php if( $_SESSION['level'] >= 100 ){ ?>
								<li class="nav-item">
									<a href="estadisticas.php" class="nav-link <?php echo $active['stats']; ?>">
										<i class=" nav-icon fas fa-chart-line"></i>
										<p>Estadisticas</p>
									</a>
								</li>
								<?php } ?>
								
                        <!--  menu-is-opening menu-open -->
						<?php if( $_SESSION['level'] >= 800 ){ ?>
						<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<li class="nav-item menu-open">
							<a href="#" class="nav-link ">
								<i class="nav-icon fas fa-cog"></i>
								<p>
									Configuraciones
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								
								
								<li class="nav-item">
									<a href="parametros.php" class="nav-link <?php echo $active['parametros']; ?>">
										<i class="fa fa-cog nav-icon"></i>
										<p>Parámetros Varios</p>
									</a>
								</li>
								
								
								<li class="nav-item">
                                    <a href="appUsers.php" class="nav-link <?php echo $active['appuser']; ?>">
                                        <i class="fa fa-mobile nav-icon"></i>
                                        <p>Usuarios APP</p>
                                    </a>
                                </li>
								
								<?php if( $_SESSION['level'] >= 800 ){ ?>
								<li class="nav-item">
									<a href="newUserApp.php" class="nav-link <?php echo $active['createuserapp']; ?>">
										<i class="nav-icon fa fa-mobile"></i>
											<p>
												Crear Usuarios App
											</p>
									 </a>
								</li>
								<?php } ?>
								
							</ul>
						</li>
						</ul>
						
						<?php } ?>
					    
						
						
						<?php if( $_SESSION['level'] >= 800 ){ ?>
						<li class="nav-item  item">
							<a href="adminUsers.php" class="nav-link <?php echo $active['admin']; ?>">
								<i class="nav-icon fas fa-users"></i>
                                <p>
                                    Administradores
								</p>
                            </a>
						</li>
						
						<li class="nav-item">
							<a href="newUser.php" class="nav-link <?php echo $active['createadm']; ?>">
								<i class="nav-icon fa fa-user-tie"></i>
									<p>
										Crear Administradores
									</p>
                             </a>
                        </li>
						<?php } ?>
						
						<li class="nav-item">
							<a href="editUserAdminMyProfile.php?id=<?php echo md5($_SESSION['userid']); ?>" class="nav-link <?php echo $active['profile']; ?>">
								<i class="far fa-user nav-icon"></i>
								<p>Mi Perfil</p>
							</a>
						</li>

                        <li class="nav-item">
                            <a href="services/close.php" class="nav-link <?php echo $active['close']; ?>">
                                <i class="nav-icon far fa-window-close"></i>
                                <p>
                                    Cerrar Sessión
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
				
				</div>
            <!-- /.sidebar -->
        </aside>