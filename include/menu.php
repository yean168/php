<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <img src="../image/duc.jpg" alt="" style="width:40px; height:40px; object-fit:cover; border-radius:50%;">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <div class="mx-auto">
        <ul class="navbar-nav justify-content-center mb-2 mb-lg-0">
          <li class="nav-item" onclick="setActiveLi(this)">
            <a 
              class="nav-link <?php echo ($active == 'home') ? 'active' : ''; ?>" 
              aria-current="page" 
              href="../page/home.php"
            >
              Home
            </a>
            <button class="btn btn-sm btn-secondary mt-2 d-none show-btn" type="button">
              Home Button
            </button>
          </li>

          <li class="nav-item" onclick="setActiveLi(this)">
            <a 
              class="nav-link <?php echo ($active == 'information') ? 'active' : ''; ?>" 
              aria-current="page" 
              href="../page/infomation.php"
            >
              Information
            </a>
            <button class="btn btn-sm btn-secondary mt-2 d-none show-btn" type="button">
              Info Button
            </button>
          </li>
        </ul>
      </div>



      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
