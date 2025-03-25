<?php
get_header();

$id = get_the_ID();
$page = get_post($id);
$experts = get_experts();

$locations_list = get_terms([
    'taxonomy'   => 'location',
    'hide_empty' => false,
]);

$industry_list = get_terms([
    'taxonomy'   => 'industry',
    'hide_empty' => false,
]);
?>

<section class="bg bg-white container no-pad-gutters mt-5">
    <div class="row mb-5">
        <div class="col-lg-8 col-xl-8 col-12">
            <h5 style="font-weight:900">Filters</h5>

            <div class="dropdown-wrapper">
                <select id="location-filter" name="location" class="custom-dropdown">
                    <option value="all">All Locations</option>
                    <?php foreach ($locations_list as $location) : ?>
                        <option value="<?php echo esc_attr($location->slug); ?>">
                            <?php echo esc_html($location->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="dropdown-wrapper">
                <select id="industry-filter" name="industry" class="custom-dropdown">
                    <option value="all">All Industries</option>
                    <?php foreach ($industry_list as $industry) : ?>
                        <option value="<?php echo esc_attr($industry->slug); ?>">
                            <?php echo esc_html($industry->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-xl-4 col-12 mt-xl-0 mt-lg-0 mt-4">
            <h5 style="font-weight:900">Search</h5>
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="quick-search" class="form-control mt-2" placeholder="Search...">
            </div>
        </div>
    </div>

    <div class="row mb-3" id="experts-container">
        <?php foreach ($experts as $expert): 
            $image_url = get_the_post_thumbnail_url($expert->ID) ?: '';
            $position = get_post_meta($expert->ID, 'position', true) ?: 'Not specified';

            $industries = get_field('industry', $expert->ID);
            $industry_slugs = [];
            if (!empty($industries) && is_array($industries)) {
                foreach ($industries as $industry) {
                    if ($industry instanceof WP_Term) {
                        $industry_slugs[] = esc_attr($industry->slug);
                    }
                }
            }

            $location = get_field('location', $expert->ID);
            $location_slug = ($location instanceof WP_Term) ? esc_attr($location->slug) : '';

            $phone_number = get_field('phone_number', $expert->ID);
            $phone_number = !empty($phone_number) ? esc_attr($phone_number) : '';

            $email = get_field('email', $expert->ID);
            $email = !empty($email) ? esc_attr($email) : '';

            $linkedIn = get_field('linkedin', $expert->ID);
            $linkedIn = !empty($linkedIn) ? esc_url($linkedIn) : '';
        ?>
            <div class="col-md-3 col-12 mb-5 text-center expert-card"
                data-location="<?php echo $location_slug; ?>"
                data-industry="<?php echo implode(',', $industry_slugs); ?>"
                data-name="<?php echo esc_attr(strtolower($expert->post_title)); ?>">

                <a href="<?php echo esc_url($expert->guid); ?>" class="expert-link d-block p-4 rounded">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($expert->post_title); ?>" class="img-fluid rounded-circle expert-image">
                    <div class="mt-3">
                        <p class="font-weight-bold mb-1"><?php echo esc_html($expert->post_title); ?></p>
                        <p class="text-muted small"><?php echo esc_html($position); ?></p>
                        <div class="d-flex justify-content-center expert-card-desc">
                            <div>
                                <p class="text-muted mb-1">Location</p>
                                <p class="font-weight-bold">
                                    <?php echo $location ? esc_html($location->name) : 'Not specified'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <div class="d-flex justify-content-center align-items-center contact-info-wrapper">
                    <a href="tel:<?php echo $phone_number; ?>" class="icon-circle"><i class="fas fa-phone"></i></a>
                    <a href="mailto:<?php echo $email; ?>" class="icon-circle mx-2"><i class="fas fa-envelope"></i></a>
                    <a href="<?php echo $linkedIn; ?>" target="_blank" class="icon-circle"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const locationFilter = document.getElementById("location-filter");
        const industryFilter = document.getElementById("industry-filter");
        const searchInput = document.getElementById("quick-search");
        const expertCards = document.querySelectorAll(".expert-card");

        document.querySelectorAll(".icon-circle").forEach((icon) => {
            icon.addEventListener("click", () => {
                const phone = icon.getAttribute("data-phone");
                const email = icon.getAttribute("data-email");
                const linkedin = icon.getAttribute("data-linkedin");
                console.log(icon);
                

                // if (phone) {
                //     window.location.href = `tel:${phone}`;
                // } else if (email) {
                //     window.location.href = `mailto:${email}`;
                // } else if (linkedin) {
                //     window.open(linkedin, "_blank");
                // }
            });
        });

        const filterExperts = () => {
            const selectedLocation = locationFilter.value;
            const selectedIndustry = industryFilter.value;
            const searchQuery = searchInput.value.toLowerCase();

            expertCards.forEach(card => {
                const expertLocation = card.getAttribute("data-location");
                const expertIndustries = card.getAttribute("data-industry").split(",");
                const expertName = card.getAttribute("data-name");

                const locationMatch = selectedLocation === "all" || expertLocation === selectedLocation;
                const industryMatch = selectedIndustry === "all" || expertIndustries.includes(selectedIndustry);
                const nameMatch = expertName.includes(searchQuery);

                if (locationMatch && industryMatch && nameMatch) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        };

        locationFilter.addEventListener("change", filterExperts);
        industryFilter.addEventListener("change", filterExperts);
        searchInput.addEventListener("keyup", filterExperts);
    });
</script>


<?php
get_footer();
