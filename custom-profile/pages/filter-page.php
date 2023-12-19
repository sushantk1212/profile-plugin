<?php
/**
 * Template Name: Profile Page
 *
 * @package customprofile
 */

get_header();
?>
	<main id="primary" class="site-main">
		<?php
		while ( have_posts() ) :
			the_post();

			// get_template_part( 'template-parts/filter-page-template.php' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
		</main><!-- #main -->

		<?php

		/* Custom filter UI counterpart */

	    $skillsTaxonomy = 'skills'; // skills taxonomy.
		$educationTaxonomy = 'education'; // education taxonomy.

		// Get the terms for the skills taxonomy.
		$skillsTerms = get_terms( array(
			'taxonomy' => $skillsTaxonomy,
			'hide_empty' => true, // Set to true if you want to hide empty skills.
		) );

		// Get the terms for the education taxonomy.
		$educationTerms = get_terms( array(
			'taxonomy' => $educationTaxonomy,
			'hide_empty' => true, // Set to true if you want to hide empty education.
		) );
		?>
		<div class="profile-filter-page ui-advance-filter">

			<form name="advance-filter" class="advance-filter" id="advance-filter"
			 action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
			 	<div class="keyword-cls">
					<label for="keyword">Keyword</label>
					<input type="text" id="keyword" name="keyword">
				</div>
				
				<div class="skills-education">
				<div class="skills-cls">
					<label for="skills">Skills</label>
					<select class="js-example-basic-multiple" id="skills" name="skills[]" multiple="multiple">
						<?php
						 foreach ( $skillsTerms as $skillsTerm ) {
							if ( ! empty( $skillsTerms ) ) {
								$skillName = $skillsTerm->name;
								$skillSlug = $skillsTerm->slug;
						?>
						<option value="<?php echo $skillName;?>"><?php echo esc_html($skillName);?></option>
						<?php
					      	}
						 }
						?>
					</select>
				</div>
				
				<div class="education-cls">
					<label for="education">Education</label>
					<select class="js-example-basic-multiple" id="education" name="education[]" multiple="multiple">
					<?php
						 foreach ( $educationTerms as $educationTerm ) {
							if ( ! empty( $educationTerms ) ) {
								$educationName = $educationTerm->name;
								$educationSlug = $educationTerm->slug;
						?>
						<option value="<?php echo $educationName;?>"><?php echo esc_html($educationName);?></option>
						<?php
					      	}
						 }
						?>
					</select>
				</div>
				</div>

				<div class="age-ratings">
					<div class="age-cls">
						<label for="age">Age</label>
						<input type="range" class="range" value="0" min="0" id="age" name="age" 
						oninput="this.nextElementSibling.value = this.value" required>
						<output>0</output>
					</div>
				
				<div class="rating-cls">
				<label for="ratings">Ratings</label>
				<div class="rating star-rating">
					<label>
						<input type="radio" id="ratings" name="stars" value="1" />
						<span class="fa fa-star icon"></span>
					</label>
					<label>
						<input type="radio" name="stars" value="2" />
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
					</label>
					<label>
						<input type="radio" name="stars" value="3" />
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
					</label>
					<label>
						<input type="radio" name="stars" value="4" />
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
					</label>
					<label>
						<input type="radio" name="stars" value="5" />
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
						<span class="fa fa-star icon"></span>
					</label>
					</div>
				   </div>
				</div>

			    <div class="jobs-experience-profile">
					<div class="profile-jobs">
						<label for="noOfJobs">No of jobs completed</label>
						<input type="number" id="noOfJobs" name="noOfJobs" min="1" required>
					</div>
					
					<div class="profile-experience">
						<label for="experience">Years of experience</label>
						<input type="number" id="experience" name="experience" min="1" required>
					</div>
				</div>
				<input type="submit" name="submit" class="submit-btn" value="SEARCH">
			</form>

		</div>
		
<table id="advancedFilter" class="display profiletable"></table>

<?php
get_footer();
