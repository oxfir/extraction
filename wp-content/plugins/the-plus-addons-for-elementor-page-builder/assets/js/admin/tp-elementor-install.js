jQuery(document).on("click", ".tpae-ele-btn", function (e) {
    e.preventDefault();

    let btn = jQuery(this);
    let slug = btn.data("slug");
    let name = btn.data("name");

    let currentText = btn.text().trim();

    if (currentText === "Install Now") {
        btn.text("Installing...");
    } else if (currentText === "Activate Now") {
        btn.text("Activating...");
    }

    jQuery.ajax({
        url: tpae_admins_js.ajax_url,
        type: "POST",
        data: {
            action: "tpae_elementor_ajax_call",
            nonce: tpae_admins_js.tpae_nonce,
            slug: slug,
            name: name
        },
        success: function (response) {

            if (response.success) {
                location.reload();
            } else {
                console.log(response.data?.message || "Failed!");
                btn.text(currentText);
            }
        },
        error: function () {
            console.log("Something went wrong!");
            btn.text(currentText);
        }
    });
});
