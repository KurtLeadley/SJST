<!-- # footer.inc.php - Script 9.2 -->
  </div>
	  <div class="footerBox">
        <footer class="footer-grid wrapper">
            <article>
                <h1>SJS Forum</h1>
                  <ul>
                      <li><a href="index.php">Home</a></li>
                      <li><a href="archive.php">Archives</a></li><li><a href="#">Contact</a></li>
                      <li><?php if ($user) { echo '<a href="logout.php">Logout</a>'; } else { echo '<a href="login.php">Login</a>'; } ?></li>
                      <li><a href="register.php">Register</a></li>
                      <?php if ($user && $user->canCreatePage()) echo '<li><a href="add_page.php">Add a New Page</a></li>'; ?>
                  </ul>
            </article>
            
            <article>
                <h1>SJS Stats</h1>
                <ul>
										<li><a href="../index.php">Home</a></li>
                    <li><a href="../standings.php">Standings</a></li>
                    <li><a href="../statistics.php">Statistics</a></li>
                    <li><a href="../roster.php">Roster</a></li>
                    <li><a href="../schedule.php">Schedule</a></li>
										<li><a href="../results.php">Results</a></li>
                </ul>
            </article>
						<article>
              <h1>SJS Press</h1>
              <ul>
					<?php	
								$q = 'SELECT id, title, content, DATE_FORMAT(dateAdded, "%e %M %Y %r") AS dateAdded FROM pages ORDER BY id DESC LIMIT 6'; 
								$r = $pdo->query($q); 
								$r->setFetchMode(PDO::FETCH_CLASS, 'Page');
								while ($page = $r->fetch()) {
									$id = $page->getId();
									$title = $page->getTitle();
									echo "<li><a href='/cms/page.php?id=$id'>".$title."</a></li>";
								}
					?>
              </ul>
            </article>
        </footer>
    </div>
</body>
</html>