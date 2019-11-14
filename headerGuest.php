
<header>
    <div class="fixed-top">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="navbar-brand" href="#">petSitter</div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?contactUs">Contact us</a>
                    </li>

                </ul>
                <span class="navbar-text">
                  <?php
                  if (isset($_SESSION['user']))
                      echo "signed in as ".$_SESSION['user'];
                  else
                      echo "Not signed in";
                  ?>
                </span>
            </div>
        </nav>
    </div>
</header>





