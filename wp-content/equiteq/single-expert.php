<?php

get_header();
$id = get_the_ID();
$expert = get_expert($id);
// $industry_expertises = maybe_unserialize($expert->industry_expertise);

$position = get_post_meta($id, 'position', true) ?: 'Not specified';

$industries = get_field('industry', $expert->ID);
$industry_names = [];

if (!empty($industries) && is_array($industries)) {
    foreach ($industries as $industry) {
        if ($industry instanceof WP_Term) {
            $industry_names[] = esc_html($industry->name);
        }
    }
}

$location = get_field('location', $expert->ID);
$location_name = ($location instanceof WP_Term) ? esc_attr($location->name) : '';

$biography = get_post_meta($id, 'biography', true);
$image_url = get_the_post_thumbnail_url($id, 'full'); 

$value_to_clients = get_post_meta($id, 'value_to_clients', true) ?: 'Not specified';
$experience = get_post_meta($id, 'experience', true) ?: 'Not specified';
$other_things = get_post_meta($id, 'other_things', true) ?: 'Not specified';

$phone_number = get_field('phone_number', $expert->ID);
$phone_number = !empty($phone_number) ? esc_attr($phone_number) : '';

$email = get_field('email', $expert->ID);
$email = !empty($email) ? esc_attr($email) : '';

$linkedIn = get_field('linkedin', $expert->ID);
$linkedIn = !empty($linkedIn) ? esc_url($linkedIn) : '';
?>

<section>
    <div class="container no-pad-gutters">
        <div class="back mb-4 mb-md-5">
            <i class="fa fa-caret-left align-bottom" style="font-size: 22px;" aria-hidden="true"></i> <a
                href="javascript:void(0);" onclick="window.history.back();" class="btn-outline-success text-uppercase px-0 ml-2">Back to team</a>
        </div>

        <div class="row">
            <div class="col-12 d-flex flex-column justify-content-center align-items-center mb-5">
                <h2><?= esc_html(get_the_title()); ?></h2>

                <?php if ($position): ?>
                    <p><?= esc_html($position); ?></p>
                <?php endif; ?>

                <?php if ($location): ?>
                    <p><?= esc_html($location_name); ?></p>
                <?php endif; ?>

                <hr style="background-color: #AFCA0B;width: 50%;">

                <div class="d-flex justify-content-center align-items-center my-3">
                    <a href="tel:<?php echo $phone_number; ?>" class="icon-circle"><i class="fas fa-phone"></i></a>
                    <a href="mailto:<?php echo $email; ?>" class="icon-circle mx-2"><i class="fas fa-envelope"></i></a>
                    <a href="<?php echo $linkedIn; ?>" target="_blank" class="icon-circle"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        
        <div class="row align-items-start expert-details">
            <div class="col-md-4 text-center">
                <?php if ($image_url): ?>
                    <img src="<?= esc_url($image_url); ?>" alt="<?= esc_attr(get_the_title()); ?>" class="img-fluid rounded">
                <?php else: ?>
                    <p>No Image Available</p>
                <?php endif; ?>
            </div>

            <div class="col-md-8 mt-xl-0 mt-lg-0 mt-md-0 mt-4">
                <?php if ($biography): ?>
                    <div class="mb-4">
                        <p> <?= esc_html($biography); ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($value_to_clients): ?>
                    <div class="mb-4">
                        <h2 class="gradient-text">Value to Clients</h2>
                        <p><?= wp_kses_post($value_to_clients); ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($industry_names)): ?>
                    <p><strong>Industry Expertise:</strong> <?= implode(', ', $industry_names); ?></p>
                <?php endif; ?>

                <?php if ($experience): ?>
                    <div class="mb-4">
                        <h2 class="gradient-text">Experience</h2>
                        <p><?= wp_kses_post($experience); ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($other_things): ?>
                    <div>
                        <h2 class="gradient-text">Other Things</h2>
                        <p><?= wp_kses_post($other_things); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


<section>
    <div class="container no-pad-gutters">
        <div class="row">
            <div class="col-12">
            <?php if (!empty($industry_names)): ?>
                <h4 style="text-align: center;font-weight: 900;">Industry Expertise</h4>
                <div class="industry-container">
                    <?php foreach ($industry_names as $industry): ?>
                        <div class="industry-item"><?= esc_html($industry); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();