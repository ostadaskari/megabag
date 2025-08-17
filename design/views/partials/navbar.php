
    <!--########## navbar ###########-->
      <div class="container px-0 div-navbar">
        <div class="row">
          <div class="col-12">
            <!-- Main Navbar -->
            <nav class="navbar navbar-custom navbar-expand-lg d-flex flex-column flex-md-row align-items-center ">
              
              <!-- Top Section: Logo, Title -->
              <div class="d-flex w-100 justify-content-center justify-content-md-start align-items-center">
                <div class="d-flex align-items-center">
                  <img src="../../design/assets/img/Logo-main.png" alt="logo" class="logo" />
                  <h4 class="m-0 navbar-title text-center ms-2">MegaBag</h4>
                </div>
              </div>

             <!-- Bottom Section: Clock and Profile , Hamburger Button -->
                  <div class="d-flex flex-row align-items-center justify-content-end div-clock-btn p-0 flex-wrap">

                    <!-- btn logout -->
                    <div class="order-1 order-md-3">
                      <a class="d-flex align-items-center p-1" href="logout.php">
                        <svg width="22" height="22" fill="#fff" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                          <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>
                      </a>
                    </div>

                    <!-- userNameTop mobile -->
                    <div class="d-md-none order-2 order-md-1">
                      <span style="color:#fff;font-size: 12px;"><?= htmlspecialchars($_SESSION['nickname'] ?? '') ?></span>
                    </div>

                    <!-- Clock & Hamburger -->
                    <div class="d-flex flex-row align-items-center order-3 order-md-2">
                      <!-- Clock -->
                      <div class="d-flex clock-container">
                        <span id="clock" class="px-1"><?= date("Y/n/d"); ?></span> |
                        <span id="date" class="px-1"><?= date("G:i "); ?></span>
                      </div>
                      <!-- Hamburger Button (mobile only) -->
                      <button class="btn d-md-none" type="button" id="toggleBtn">
                        <svg width="22" height="22" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                          <path fill-rule="evenodd"
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                        </svg>
                      </button>
                    </div>

                  </div>

              <!-- End of Bottom Section -->
            </nav>
          </div>
        </div>
      </div>
    <!--########## end navbar ###########-->
