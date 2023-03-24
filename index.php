<?php

include( 'admin/includes/database.php' );

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="styles/styles.css" />
		<link rel="icon" type="image/x-icon" href="images/favicon.png" />
		<script src="js/jquery.js"></script>
		<title>Raymond Lee</title>
	</head>
	<body>
		<header id="header">
			<div id="header-content">
				<a id="home-logo" href="#home"></a>
				<div id="header-content-right" class="flex-center">
					<a
						class="logo linkedin-logo"
						href="https://www.linkedin.com/in/raymondleemv"
						target="_blank"
					></a>
					<a
						class="logo github-logo"
						href="https://github.com/raymondleemv"
						target="_blank"
					></a>
					<nav id="navbar">
						<button
							id="header-dropdown-menu"
							class="dropdown-menu flex-center flex-column"
						>
							<div class="dropdown-menu-stripes"></div>
							<div class="dropdown-menu-stripes"></div>
							<div class="dropdown-menu-stripes"></div>
						</button>
						<ul>
							<li><a href="#about">About</a></li>
							<li><a href="#projects">Projects</a></li>
							<li><a href="#skills">Skills</a></li>
							<li><a href="#contact">Contact</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</header>
		<main>
			<section id="home">
				<div id="home-content-container" class="flex-center flex-column">
					<div id="home-content" class="flex-center flex-column">
						<h1 id="hero-text-name">Raymond Lee</h1>
						<h2 id="hero-text-intro">- A Full Stack Web Developer -</h2>
						<h2 id="tagline">
							Coding is not just a language, it's also a part of me.
						</h2>
					</div>
					<div id="read-more" class="flex-center flex-column">
						<h2 id="read-more-text">- Read more -</h2>
						<img
							alt="This is a picture of the read more arrow prompting the user that there's more content below"
							id="read-more-arrow"
							src="images/home/eject.png"
						/>
						<div class="separating-line"></div>
					</div>
				</div>
			</section>
			<section id="about">
				<h2 class="section-title">About Raymond</h2>
				<div id="about-content" class="flex-center">
					<div class="flex-center flex-column">
						<p id="about-content-intro">
							I have always enjoyed coding and solving puzzles in my free time
							when I were young, and here I am now! Becoming a Full Stack Web
							Developer based in Toronto, Ontario.
						</p>
						<div id="about-buttons" class="flex-center">
							<a id="about-work-button" href="#projects" class="button"
								>View My Work</a
							>
							<a id="about-contact-button" href="#contact" class="button"
								>Contact Me</a
							>
						</div>
					</div>
					<img
						id="about-self-portrait"
						src="images/about/raymond.JPG"
						alt="This is a picture of Raymond"
					/>
					<img
						src="images/about/soft-skills.png"
						alt="This is a pentagon graph showing Raymond's soft skills"
					/>
				</div>
				<h3 class="section-subtitle">Education</h3>
				<div class="about-hover-cards-container">
					<?php

					$query = 'SELECT c.career_id, c.career, c.location, c.start_date, c.end_date, c.photo, GROUP_CONCAT(s.name SEPARATOR " / ") AS skills
					FROM career c
					JOIN career_skills cs
					ON c.career_id = cs.career_id
					JOIN skills s
					ON s.id = cs.skills_id
					WHERE c.user_id = 3
					AND c.career_type_id = 1
					GROUP BY c.career_id';
					$result = mysqli_query( $connect, $query );
					while( $record = mysqli_fetch_assoc( $result ) ):
						echo '<div class="flex-center hover-card flex-column">';
						/* https://international-sustainable-campus-network.org/membership/the-hong-kong-university-of-science-and-technology/ */
						/* https://humber.ca/brand/humber-logo-sub-brand-logos */
						echo '<img src='.$record['photo'].'>';
						$career_start = strpos($record['career'], '(');
						if ($career_start !== false) {
							echo '<p>'.substr($record['career'], 0, $career_start).'</p>';
							echo '<p>'.substr($record['career'], $career_start + 1, -1).'</p>';
						} else {
							echo '<p>'.$record['career'].'</p>';
						}
						echo '<p>'.$record['location'].'</p>';
						$start_year = explode('-', $record['start_date'])[0];
						$end_year = explode('-', $record['end_date'])[0];
						echo '<p>'.$start_year.' - '.$end_year.'</p>';
						echo '<p>- '.$record['skills'].' -</p>';
						echo '</div>';
					endwhile;

					?>
				</div>
				<h3 class="section-subtitle">Experience</h3>
				<div class="about-hover-cards-container">
					<?php

					$query = 'SELECT c.career_id, c.career, c.location, c.start_date, c.end_date, c.photo, GROUP_CONCAT(s.name SEPARATOR " / ") AS skills
					FROM career c
					JOIN career_skills cs
					ON c.career_id = cs.career_id
					JOIN skills s
					ON s.id = cs.skills_id
					WHERE c.user_id = 3
					AND c.career_type_id = 2
					GROUP BY c.career_id';
					$result = mysqli_query( $connect, $query );
					while( $record = mysqli_fetch_assoc( $result ) ):
						echo '<div class="flex-center hover-card flex-column">';
						/* https://smt.asmpt.com/en/news-center/press/asm-pacific-technology-announces-2020-annual-results/ */
						/* https://www.linkedin.com/company/gensetech/?originalSubdomain=hk */
						echo '<img src='.$record['photo'].'>';
						$career_start = strpos($record['career'], '(');
						if ($career_start !== false) {
							echo '<p>'.substr($record['career'], 0, $career_start - 1).'</p>';
							echo '<p>'.substr($record['career'], $career_start, -1).'</p>';
						} else {
							echo '<p>'.$record['career'].'</p>';
						}
						echo '<p>'.$record['location'].'</p>';
						$start_year = explode('-', $record['start_date'])[0];
						$end_year = explode('-', $record['end_date'])[0];
						echo '<p>'.$start_year.' - '.$end_year.'</p>';
						echo '<p>- '.$record['skills'].'- </p>';
						echo '</div>';
					endwhile;

					?>
				</div>
			</section>
			<section id="projects">
				<h2 class="section-title">Projects</h2>
				<div id="projects-container">
					<div id="projects-image-container">
						<?php

						$query = 'SELECT p.title, p.content, p.url, p.github, p.photo, GROUP_CONCAT(s.name SEPARATOR " / ") AS skills
						FROM projects p
						JOIN projects_skills ps
						ON p.id = ps.project_id
						JOIN skills s
						ON s.id = ps.skill_id
						WHERE p.user_id = 3
						GROUP BY p.id';
						$result = mysqli_query( $connect, $query );
						while( $record = mysqli_fetch_assoc( $result ) ):
							echo '<img class="project-image" src='.$record['photo'].' alt="This is the '.$record['title'].' project image">';
						endwhile;

						?>
					</div>
					<div id="projects-description-container">
						<div id="project-number-container">
							<p id="current-project-number"></p>
							<p id="total-project-number"></p>
						</div>
						<div id="projects-description-content-container">
							<div id="projects-description-content">
								<?php
									$query = 'SELECT p.title, p.content, p.url, p.github, p.photo, GROUP_CONCAT(s.name SEPARATOR " / ") AS skills
									FROM projects p
									JOIN projects_skills ps
									ON p.id = ps.project_id
									JOIN skills s
									ON s.id = ps.skill_id
									WHERE p.user_id = 3
									GROUP BY p.id';
									$result = mysqli_query( $connect, $query );
									while( $record = mysqli_fetch_assoc( $result ) ):
										echo '<div class="project-description">';
										echo '<h3 class="section-subtitle">'.$record['title'].'</h3>';
										echo '<p>'.$record['content'].'</p>';
										echo '<div class="project-description-links">';
										if ($record['url']) {
											echo '<a class="button" href="'.$record['url'].'" target="_blank">Visit</a>';
										}
										echo '<a class="logo github-logo" href="'.$record['github'].'" target="_blank"></a>';
										echo '</div>'; // end of project-description-links div
										echo '</div>'; // end of project-description div
									endwhile;
								?>
								<!-- <div class="project-description">
									<h3 class="section-subtitle">CT Image Segmentation</h3>
									<p>
										This is the Final Year Project of my Bachelor’s program in
										which I built a CT Volume Segmentation Softwre using the
										GUIDE feature of MATLAB.
									</p>
									<div class="project-description-links">
										<a
											class="logo github-logo"
											href="https://github.com/raymondleemv/ECE_FYP2021_YW03a-20"
											target="_blank"
										></a>
									</div>
								</div>
								<div class="project-description">
									<h3 class="section-subtitle">Top Gun Maverick</h3>
									<p>
										A javascript game to challenge your reaction time and
										dodging skills.
									</p>
									<div class="project-description-links">
										<a class="button">Visit</a>
										<a
											class="logo github-logo"
											href="https://github.com/raymondleemv/Top-Gun-Maverick"
											target="_blank"
										></a>
									</div>
								</div>
								<div class="project-description">
									<h3 class="section-subtitle">Cross The Bridge</h3>
									<p>
										A puzzle to challenge your logical thinking and problem
										solving skills.
									</p>
									<div class="project-description-links">
										<a class="button">Visit</a>
										<a
											class="logo github-logo"
											href="https://github.com/raymondleemv/Cross-The-Bridge"
											target="_blank"
										></a>
									</div>
								</div> -->
							</div>
							<div id="projects-description-arrows">
								<button
									class="arrow"
									id="projects-description-prev-btn"
								></button>
								<button id="projects-description-next-btn"></button>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section id="skills" class="flex-center flex-column">
				<h2 class="section-title">Skills</h2>
				<div id="skills-content-container">
					<div id="hard-skills-container" class="flex-center">
						<?php

						$query = 'SELECT s.name, s.user_id, s.photo
						FROM skills s
						WHERE s.user_id = 3';
						$result = mysqli_query( $connect, $query );
						while( $record = mysqli_fetch_assoc( $result ) ):
							echo '<div class="hard-skills-content">';
							echo '<img alt="This is the '.$record['name'].' skill icon" src='.$record['photo'].'>';
							echo '<p>'.$record['name'].'</p>';
							echo '</div>';
						endwhile;

						?>
					</div>
				</div>
				<div class="separating-line"></div>
			</section>
			<section id="contact" class="flex-center flex-column">
				<h2 class="section-title">Let's Chat!</h2>
				<div id="contact-chatbox-container">
					<div id="contact-chatbox-header">Send me a message!</div>
					<div id="contact-chatbox-content-container">
						<p class="contact-chatbox-content contact-chatbox-left">
							If you are interested in my CV, you can download it
							<a href="CV/Resume_ManViewRaymondLee.pdf" download>here</a>
						</p>
						<div class="contact-chatbox-content contact-chatbox-left">
							<p>I am reachable at:</p>
							<div class="contact-chatbox-contact-info">
								<img
									alt="This is a phone icon"
									src="images/contact/phone.png"
									class="contact-chatbox-contact-info-icon"
								/>
								<p class="contact-chatbox-contact-info-content">
									(437) 997 5066
								</p>
							</div>
							<div class="contact-chatbox-contact-info">
								<img
									alt="This is a email icon"
									src="images/contact/paper-plane.png"
									class="contact-chatbox-contact-info-icon"
								/>
								<p class="contact-chatbox-contact-info-content">
									raymondleemv@gmail.com
								</p>
							</div>
							<a
								class="logo linkedin-logo"
								href="https://www.linkedin.com/in/raymondleemv"
								target="_blank"
							></a>
							<a
								class="logo github-logo"
								href="https://github.com/raymondleemv"
								target="_blank"
							></a>
						</div>
						<p class="contact-chatbox-content contact-chatbox-left">
							Or you can also fill the contact form!
						</p>
						<form class="contact-form">
							<div class="contact-chatbox-content contact-chatbox-right">
								<input
									class="contact-form__field contact-form__name"
									placeholder="Name"
								/>
							</div>
							<div class="contact-chatbox-content contact-chatbox-right">
								<input
									class="contact-form__field contact-form__email"
									placeholder="Email"
								/>
							</div>
							<div class="contact-chatbox-content contact-chatbox-right">
								<input
									class="contact-form__field contact-form__subject"
									placeholder="Subject"
								/>
							</div>
							<div class="contact-chatbox-content contact-chatbox-right">
								<textarea
									class="contact-form__field contact-form__message"
									placeholder="Message"
								></textarea>
							</div>
							<button class="button contact-chatbox-right contact-form__submit">
								Send
							</button>
						</form>
					</div>
				</div>
				<div class="separating-line"></div>
			</section>
		</main>
		<footer>
			<div>- &copy; 2022 Man View Raymond Lee. -</div>
		</footer>
		<script src="js/Portfolio.js"></script>
	</body>
</html>
