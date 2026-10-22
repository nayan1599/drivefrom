<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	<div class="container">
		<a class="navbar-brand" href="index.html">Car<span>Book</span></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
			aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="oi oi-menu"></span> Menu
		</button>

		<?php $active = $_GET['page'] ?? 'home'; ?>

		<div class="collapse navbar-collapse" id="ftco-nav">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item <?php echo $active === 'home' ? 'active' : ''; ?>">
					<a href="index.php" class="nav-link">Home</a>
				</li>
				<li class="nav-item <?php echo $active === 'about' ? 'active' : ''; ?>">
					<a href="index.php?page=about" class="nav-link">About</a>
				</li>
				<li class="nav-item <?php echo $active === 'services' ? 'active' : ''; ?>">
					<a href="index.php?page=services" class="nav-link">Services</a>
				</li>
				<li class="nav-item <?php echo $active === 'pricing' ? 'active' : ''; ?>">
					<a href="index.php?page=pricing" class="nav-link">Pricing</a>
				</li>
				<li class="nav-item <?php echo $active === 'car' ? 'active' : ''; ?>">
					<a href="index.php?page=car" class="nav-link">Cars</a>
				</li>
				<li class="nav-item <?php echo $active === 'blog' ? 'active' : ''; ?>">
					<a href="index.php?page=blog" class="nav-link">Blog</a>
				</li>
				<li class="nav-item <?php echo $active === 'contact' ? 'active' : ''; ?>"><a
						href="index.php?page=contact" class="nav-link">Contact</a></li>
			</ul>
		</div>
	</div>
</nav>