<?php
/**
 * The template for displaying all single posts
 *
 * @package a2n-base
 */

get_header();
?>
	</div>
	<section id="primary" class="about_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="detail-box">
            <div class="heading_container">
            	<h1><?php the_title();?></h1>
            </div>
			<?php while ( have_posts() ) : the_post(); // Loop ?>
			<?php
				 $profileDob = get_post_meta($post->ID, 'profile_dob', true); // get DOB
				 $years_of_exp = get_post_meta($post->ID, 'years_of_exp', true); // get Years of Experience
				 $profileRatings = get_post_meta($post->ID, 'profileratings', true); // get Profile Ratings
				 $noJobsCompleted = get_post_meta($post->ID, 'no_jobs_completed', true); // get no of jobs completed

				 $skillsTaxonomy = 'skills'; // skills taxonomy.
				 $educationTaxonomy = 'education'; // education taxonomy.

				 $hobbies = 'hobbies';
				 $interests = 'interests';

				$skillsTerms = get_the_terms( $post->ID, $skillsTaxonomy );
				$educationTerms = get_the_terms( $post->ID, $educationTaxonomy );
				$hobbiesTerms = get_the_terms( $post->ID, $hobbies );
				$interestsTerms = get_the_terms( $post->ID, $interests );
			?>
			<div class="entry-content">
           		<?php 
					the_content();
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

					echo '<h4>Date of Birth: '.$profileDob.'</h4>';
					echo '<h4>Rating: '.$profileRatings.'</h4>';
					echo '<h4>No of jobs completed: '.$noJobsCompleted.'</h4>';
					echo '<h4>Years of Experience: '.$years_of_exp.'</h4>';
				?>

				<?php if(!empty($skillsTerms)):?>
					<h5>Skills</h5>
					<ul>
						<?php foreach($skillsTerms as $skillsTerm):?>
							<li><?php echo $skillsTerm->name;?></li>
						<?php endforeach;?>
					</ul>
				<?php endif;?>

				<?php if(!empty($educationTerms)):?>
					<h5>Education</h5>
					<ul>
						<?php foreach($educationTerms as $educationTerm):?>
							<li><?php echo $educationTerm->name;?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif;?>

				<?php if(!empty($hobbiesTerms)):?>
					<h5>Hobbies</h5>
					<ul>
						<?php foreach($hobbiesTerms as $hobbiesTerm):?>
							<li><?php echo $hobbiesTerm->name;?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif;?>
				
				<?php if(!empty($interestsTerms)):?>
					<h5>Interests</h5>
					<ul>
						<?php foreach($interestsTerms as $interestsTerm):?>
							<li><?php echo $interestsTerm->name;?></li>
						<?php endforeach;?>
					</ul>
				<?php endif;?>
			</div>
			<?php endwhile;
			unset($profileDob, $years_of_exp, $profileRatings, $noJobsCompleted, $skillsTerms, 
			$educationTerms, $hobbiesTerms, $interestsTerms);
			?>

			<?php
				the_post_navigation(
						array(
							'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'sushant-theme' ) . '</span> <span class="nav-title">%title</span>',
							'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'sushant-theme' ) . '</span> <span class="nav-title">%title</span>',
						)
				);?>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php get_footer();
