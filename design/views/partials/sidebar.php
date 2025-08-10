<?php
$role = $_SESSION['role'] ?? 'guest';
?>
        <div class="col-2 d-none d-md-flex sidebar flex-column position-relative pt-2">
           <div class="userNameTop">
            <svg width="50" height="50" fill="var(--main-bg1-color)" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
              </svg>
                 <div class="d-flex flex-column">
                  <span class="my-2" style="color: var(--main-bg0-color);"><?= htmlspecialchars($_SESSION['nickname'] ?? '') ?></span>
                  <span class="mb-1" style="font-size: 12px;color:rgb(219, 234, 255);"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></span>
                 </div> 
            </div>
          <ul class="list-unstyled px-2">


            <?php if ($role === 'admin'): ?>
                          <!-- Dashboard -->
            <li class="menu-item" data-content="Dashbord">
              <a href="?page=home" class="menu-link">
                <svg width="22" height="22" fill="var(--main-hover-color)" class="bi bi-border-outer" viewBox="0 0 16 16">
                  <path d="M7.5 1.906v.938h1v-.938zm0 1.875v.938h1V3.78h-1zm0 1.875v.938h1v-.938zM1.906 8.5h.938v-1h-.938zm1.875 0h.938v-1H3.78v1zm1.875 0h.938v-1h-.938zm2.813 0v-.031H8.5V7.53h-.031V7.5H7.53v.031H7.5v.938h.031V8.5zm.937 0h.938v-1h-.938zm1.875 0h.938v-1h-.938zm1.875 0h.938v-1h-.938zM7.5 9.406v.938h1v-.938zm0 1.875v.938h1v-.938zm0 1.875v.938h1v-.938z"/>
                  <path d="M0 0v16h16V0zm1 1h14v14H1z"/>
                </svg>
                <span>Dashboard</span>
              </a>
            </li>
             <!-- Manage users -->
            <li class="menu-item">
              <a href="#" class="menu-link">
                <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-person-add" viewBox="0 0 16 16">
                  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                  <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                </svg>
                <span>Manage users</span>
              </a>
              <!-- subMenu-->
              <ul class="submenu list-unstyled">
                <li><a href="?page=invite_users" >Invite User</a></li>
                <li><a href="?page=manage_users" >Users List</a></li>
              </ul>
            </li>
            <?php endif; ?>

            <?php if ($role === 'admin' || $role === 'manager'): ?>

            <!-- Add to Product -->
              <li class="menu-item">
                <a href="#" class="menu-link">
                  <svg width="22" height="22" fill="var(--main-hover-color)" class="bi bi-terminal-plus" viewBox="0 0 16 16">
                      <path d="M2 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h5.5a.5.5 0 0 1 0 1H2a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v4a.5.5 0 0 1-1 0V4a1 1 0 0 0-1-1z"/>
                      <path d="M3.146 5.146a.5.5 0 0 1 .708 0L5.177 6.47a.75.75 0 0 1 0 1.06L3.854 8.854a.5.5 0 1 1-.708-.708L4.293 7 3.146 5.854a.5.5 0 0 1 0-.708M5.5 9a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                    </svg>
                  <span>Product</span>
                </a>
                <ul class="submenu list-unstyled">
                  <li><a href="?page=create_product" >Add a single part</a></li>
                  <li><a href="?page=products_list" >List of Product</a></li>
                  
                </ul>
              </li>
                          <!-- Add feature to category -->
              <li class="menu-item">
                <a href="#" class="menu-link">
                  <svg width="22" height="22" fill="var(--main-hover-color)" class="bi bi-terminal-plus" viewBox="0 0 16 16">
                      <path d="M2 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h5.5a.5.5 0 0 1 0 1H2a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v4a.5.5 0 0 1-1 0V4a1 1 0 0 0-1-1z"/>
                      <path d="M3.146 5.146a.5.5 0 0 1 .708 0L5.177 6.47a.75.75 0 0 1 0 1.06L3.854 8.854a.5.5 0 1 1-.708-.708L4.293 7 3.146 5.854a.5.5 0 0 1 0-.708M5.5 9a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                    </svg>
                  <span>Features</span>
                </a>
                <ul class="submenu list-unstyled">
                  <li><a href="?page=add_category_feature" >Add features to a Category</a></li>
                  <li><a href="?page=product_feature_values" >Product feature value</a></li>
                  
                </ul>
              </li>
              <!-- Insert -->
              <li class="menu-item">
                <a href="#" class="menu-link">
                  <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0z"/>
                    <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                  </svg>
                  <span>Stock In</span>
                </a>
                <!-- subMenu-->
                <ul class="submenu list-unstyled">
                  <li><a href="?page=receive_stock"  >Insert Items</a></li>
                  <li><a href="?page=list_receipts"  >List Of Receives</a></li>
                  <li><a href="?page=receive_csv"  >Insert By CSV</a></li>
                </ul>
              </li>

            <!-- Withdraw from Inventory -->
            <li class="menu-item">
              <a href="#" class="menu-link">
                <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                  </svg>
                <span>Stock Out</span>
              </a>
              <!-- subMenu-->
              <ul class="submenu list-unstyled">
                <li><a href="?page=stock_issue" >Withdraw Items</a></li>
                <li><a href="?page=list_issues" >List Of Withdraw</a></li>
                
              </ul>
            </li>

            <!-- Reports -->
            <li class="menu-item">
              <a href="#" class="menu-link">
                <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-flag" viewBox="0 0 16 16">
                    <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21 21 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21 21 0 0 0 14 7.655V1.222z"/>
                  </svg>
                <span>Reports</span>
              </a>
              <!-- subMenu-->
              <ul class="submenu list-unstyled">
                  <li><a href="?page=bans" >Ban List</a></li>
                  <li><a href="?page=login_logs" >Login Log</a></li>
                </ul>
            </li>


             <!-- Categories -->
             <li class="menu-item">
              <a href="?page=manage_categories" class="menu-link">
                <svg width="22" height="22" fill="var(--main-hover-color)" class="bi bi-bricks" viewBox="0 0 16 16">
                  <path d="M0 .5A.5.5 0 0 1 .5 0h15a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H14v2h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H14v2h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5H2v-2H.5a.5.5 0 0 1-.5-.5v-3A.5.5 0 0 1 .5 6H2V4H.5a.5.5 0 0 1-.5-.5zM3 4v2h4.5V4zm5.5 0v2H13V4zM3 10v2h4.5v-2zm5.5 0v2H13v-2zM1 1v2h3.5V1zm4.5 0v2h5V1zm6 0v2H15V1zM1 7v2h3.5V7zm4.5 0v2h5V7zm6 0v2H15V7zM1 13v2h3.5v-2zm4.5 0v2h5v-2zm6 0v2H15v-2z"/>
                </svg>
                <span>Categories</span>
              </a>
            </li>
            
            <?php endif; ?>

            <?php if (in_array($role, ['user', 'manager', 'admin'])): ?>
            <!-- Search-Inventory -->
            <li class="menu-item">
              <a href="?page=user_search_products" class="menu-link">
                <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-search" viewBox="0 0 16 16">
                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
                <span>Search</span>
              </a>
            </li>
            
            <!-- profile -->
             <li class="menu-item" data-content="profile">
              <a href="?page=profile" class="menu-link">
                <svg width="22" height="22" fill="var(--main-hover-color)" class="bi bi-person-square" viewBox="0 0 16 16">
                  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                </svg>
                <span>Profile</span>
              </a>
            </li>
            <?php endif; ?>

          </ul>
        </div>
       <!--***** sidebar *****-->




        <!--***** Sidebar for mobile (Offcanvas) *****-->
        <div class="offcanvas d-md-none w-100" id="sidebarMenu">

          <div class="offcanvas-body sidebar-Mobile d-flex flex-column mt-5">
            <ul class="list-unstyled">
              <!-- Dashboard -->
              <li class="menu-item">
                <a href="#" class="menu-link">
                  <svg width="22" height="22" fill="var(--main-hover-color)" class="bi bi-border-outer" viewBox="0 0 16 16">
                    <path d="M7.5 1.906v.938h1v-.938zm0 1.875v.938h1V3.78h-1zm0 1.875v.938h1v-.938zM1.906 8.5h.938v-1h-.938zm1.875 0h.938v-1H3.78v1zm1.875 0h.938v-1h-.938zm2.813 0v-.031H8.5V7.53h-.031V7.5H7.53v.031H7.5v.938h.031V8.5zm.937 0h.938v-1h-.938zm1.875 0h.938v-1h-.938zm1.875 0h.938v-1h-.938zM7.5 9.406v.938h1v-.938zm0 1.875v.938h1v-.938zm0 1.875v.938h1v-.938z"/>
                    <path d="M0 0v16h16V0zm1 1h14v14H1z"/>
                  </svg>
                  <span>Dashboard</span>
                </a>
              </li>
            <?php if ($role === 'admin'): ?>
             <!-- Manage users -->
                <li class="menu-item">
                <a href="#" class="menu-link">
                    <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-person-add" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                    <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                    </svg>
                    <span>Manage users</span>
                </a>
                <!-- subMenu-->
                <ul class="submenu list-unstyled">
                    <li><a href="../manager/invite_users.php" >Invite User</a></li>
                    <li><a href="../admin/manage_users.php" >User List</a></li>
                </ul>
                </li>
            <?php endif; ?>

            <?php if ($role === 'admin' || $role === 'manager'): ?>

              <!-- Add to Product -->
              <li class="menu-item">
                <a href="#" class="menu-link">
                  <svg width="22" height="22" fill="var(--main-hover-color)" class="bi bi-terminal-plus" viewBox="0 0 16 16">
                      <path d="M2 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h5.5a.5.5 0 0 1 0 1H2a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v4a.5.5 0 0 1-1 0V4a1 1 0 0 0-1-1z"/>
                      <path d="M3.146 5.146a.5.5 0 0 1 .708 0L5.177 6.47a.75.75 0 0 1 0 1.06L3.854 8.854a.5.5 0 1 1-.708-.708L4.293 7 3.146 5.854a.5.5 0 0 1 0-.708M5.5 9a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                    </svg>
                  <span>Product</span>
                </a>

                <ul class="submenu list-unstyled">
                  <li><a href="../manager/create_product.php" >Add a single part</a></li>
                  <li><a href="../manager/products_list.php" >List Product</a></li>
                 
                </ul>
              </li>

              <!-- Insert -->
              <li class="menu-item">
                <a href="#" class="menu-link">
                  <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0z"/>
                    <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                  </svg>
                  <span>Stock In</span>
                </a>
                <!-- subMenu-->
                <ul class="submenu list-unstyled">
                  <li><a href="?page=receive_stock"  >Insert Items</a></li>
                  <li><a href="?page=list_receipts"  >List Of Receives</a></li>
                  <li><a href="?page=receive_csv"  >Insert By CSV</a></li>
                </ul>
              </li>

            <!-- Withdraw from Inventory -->
            <li class="menu-item">
              <a href="#" class="menu-link">
                <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                  </svg>
                <span>Stock Out</span>
              </a>
              <!-- subMenu-->
              <ul class="submenu list-unstyled">
                <li><a href="?page=stock_issue" >Withdraw Items</a></li>
                <li><a href="?page=list_issues" >List Of Withdraw</a></li>
                
              </ul>
            </li>

            <!-- Reports -->
            <li class="menu-item">
              <a href="#" class="menu-link">
                <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-flag" viewBox="0 0 16 16">
                    <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21 21 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21 21 0 0 0 14 7.655V1.222z"/>
                  </svg>
                <span>Reports</span>
              </a>
              <!-- subMenu-->
              <ul class="submenu list-unstyled">
                  <li><a href="?page=bans" >Ban List</a></li>
                  <li><a href="?page=login_logs" >Login Log</a></li>
                </ul>
            </li>

            <!-- Categories -->
             <li class="menu-item" >
              <a href="?page=manage_categories" class="menu-link">
                <svg width="22" height="22" fill="var(--main-hover-color)" class="bi bi-bricks" viewBox="0 0 16 16">
                  <path d="M0 .5A.5.5 0 0 1 .5 0h15a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H14v2h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H14v2h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5H2v-2H.5a.5.5 0 0 1-.5-.5v-3A.5.5 0 0 1 .5 6H2V4H.5a.5.5 0 0 1-.5-.5zM3 4v2h4.5V4zm5.5 0v2H13V4zM3 10v2h4.5v-2zm5.5 0v2H13v-2zM1 1v2h3.5V1zm4.5 0v2h5V1zm6 0v2H15V1zM1 7v2h3.5V7zm4.5 0v2h5V7zm6 0v2H15V7zM1 13v2h3.5v-2zm4.5 0v2h5v-2zm6 0v2H15v-2z"/>
                </svg>
                <span>Categories</span>
              </a>
            </li>
            <?php endif; ?>

            <?php if (in_array($role, ['user', 'manager', 'admin'])): ?>
            <!-- Search-Inventory -->
            <li class="menu-item">
              <a href="?page=user_search_products" class="menu-link">
                <svg width="24" height="24" fill="var(--main-hover-color)" class="bi bi-search" viewBox="0 0 16 16">
                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
                <span>Search</span>
              </a>
            </li>
            
            <!-- profile -->
             <li class="menu-item" >
              <a href="?page=profile" class="menu-link">
                <svg width="22" height="22" fill="var(--main-hover-color)" class="bi bi-person-square" viewBox="0 0 16 16">
                  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                </svg>
                <span>Profile</span>
              </a>
            </li>
            <?php endif; ?>


            </ul>
          </div>
        </div>
