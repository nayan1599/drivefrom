<?php 

$errors =[];


?>
 

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">নতুন ওয়েবসাইট পেজ</h3>
            <p class="text-muted mb-0">
                ওয়েবসাইটের জন্য নতুন পেজ তৈরি করুন
            </p>
        </div>

        <a href="index.php?page=website_pages/index"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> পেজ তালিকা
        </a>
    </div>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST"
          action="admin.php?page=sql/pages_save"
          enctype="multipart/form-data">

        <div class="row g-4">

            <!-- Main Content -->
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">পেজের তথ্য</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                পেজের নাম *
                            </label>

                            <input type="text"
                                   name="title"
                                   id="pageTitle"
                                   class="form-control"
                                   placeholder="যেমন: About Us"
                                   value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Slug / URL *
                            </label>

                            <input type="text"
                                   name="slug"
                                   id="pageSlug"
                                   class="form-control"
                                   placeholder="about-us"
                                   value="<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>"
                                   required>

                            <small class="text-muted">
                                উদাহরণ: about-us, contact-us
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                পেজের কনটেন্ট
                            </label>

                            <textarea name="content"
                                      class="form-control"
                                      rows="12"
                                      placeholder="এখানে পেজের লেখা লিখুন..."><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>

                            <small class="text-muted">
                                এটি সাধারণ textarea। HTML editor চাইলে
                                পরে CKEditor বা TinyMCE যুক্ত করা যাবে।
                            </small>
                        </div>

                    </div>
                </div>

                <!-- SEO -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">SEO সেটিংস</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Meta Title</label>

                            <input type="text"
                                   name="meta_title"
                                   class="form-control"
                                   maxlength="255"
                                   value="<?= htmlspecialchars($meta_title, ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div class="mb-0">
                            <label class="form-label">
                                Meta Description
                            </label>

                            <textarea name="meta_description"
                                      class="form-control"
                                      rows="3"><?= htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">পাবলিশ সেটিংস</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Page Type *
                            </label>

                            <select name="page_type"
                                    class="form-select"
                                    required>

                                <option value="home"
                                    <?= $page_type === 'home' ? 'selected' : '' ?>>
                                    Home
                                </option>

                                <option value="about"
                                    <?= $page_type === 'about' ? 'selected' : '' ?>>
                                    About
                                </option>

                                <option value="services"
                                    <?= $page_type === 'services' ? 'selected' : '' ?>>
                                    Services
                                </option>

                                <option value="contact"
                                    <?= $page_type === 'contact' ? 'selected' : '' ?>>
                                    Contact
                                </option>

                                <option value="blog"
                                    <?= $page_type === 'blog' ? 'selected' : '' ?>>
                                    Blog
                                </option>

                                <option value="custom"
                                    <?= $page_type === 'custom' ? 'selected' : '' ?>>
                                    Custom Page
                                </option>

                            </select>
                        </div>

<!-- Featured Image -->
<div class="mb-4">

    <label class="form-label fw-semibold">
        <i class="bi bi-image me-1"></i>
        Featured Image
    </label>

    <div class="border rounded-3 p-3 text-center bg-light">

        <img
            id="featuredImagePreview"
            src="<?= !empty($featured_image)
                ? htmlspecialchars($featured_image, ENT_QUOTES, 'UTF-8')
                : '' ?>"
            alt="Featured Image Preview"
            class="img-fluid rounded mb-3"
            style="
                max-height: 180px;
                width: 100%;
                object-fit: cover;
                <?= !empty($featured_image) ? '' : 'display:none;' ?>
            "
        >

        <div id="imagePlaceholder"
             class="py-4"
             style="<?= !empty($featured_image) ? 'display:none;' : '' ?>">

            <i class="bi bi-cloud-arrow-up fs-1 text-secondary"></i>

            <p class="mb-1 fw-semibold">
                ছবি নির্বাচন করুন
            </p>

            <small class="text-muted">
                JPG, PNG, WEBP
            </small>
        </div>

        <input
            type="file"
            name="featured_image"
            id="featuredImageInput"
            class="form-control mt-2"
            accept="image/jpeg,image/png,image/webp"
        >

        <button
            type="button"
            id="removeFeaturedImage"
            class="btn btn-sm btn-outline-danger mt-2"
            style="<?= !empty($featured_image) ? '' : 'display:none;' ?>"
        >
            <i class="bi bi-trash"></i>
            ছবি সরান
        </button>

    </div>

    <small class="text-muted d-block mt-2">
        সর্বোচ্চ 2 MB ছবি ব্যবহার করুন।
    </small>

</div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Status
                            </label>

                            <select name="status" class="form-select">
                                <option value="draft"
                                    <?= $status === 'draft' ? 'selected' : '' ?>>
                                    Draft
                                </option>

                                <option value="published"
                                    <?= $status === 'published' ? 'selected' : '' ?>>
                                    Published
                                </option>

                                <option value="inactive"
                                    <?= $status === 'inactive' ? 'selected' : '' ?>>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Menu Order
                            </label>

                            <input type="number"
                                   name="sort_order"
                                   class="form-control"
                                   min="0"
                                   value="<?= $sort_order ?>">
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input type="checkbox"
                                   name="show_in_menu"
                                   id="showInMenu"
                                   class="form-check-input"
                                   value="1"
                                   <?= $show_in_menu ? 'checked' : '' ?>>

                            <label for="showInMenu"
                                   class="form-check-label">
                                মেনুতে দেখাবে
                            </label>
                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">
                            <i class="bi bi-save"></i>
                            পেজ সংরক্ষণ করুন
                        </button>

                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<script>
// Title থেকে English slug স্বয়ংক্রিয় তৈরি হবে,
// তবে হাতে Slug লিখলে সেটি পরিবর্তন করা হবে না।
const pageTitle = document.getElementById('pageTitle');
const pageSlug = document.getElementById('pageSlug');

let slugManuallyEdited = pageSlug.value.trim() !== '';

pageSlug.addEventListener('input', function () {
    slugManuallyEdited = this.value.trim() !== '';
});

pageTitle.addEventListener('input', function () {
    if (!slugManuallyEdited) {
        pageSlug.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
});
 
const imageInput = document.getElementById('featuredImageInput');
const imagePreview = document.getElementById('featuredImagePreview');
const imagePlaceholder = document.getElementById('imagePlaceholder');
const removeButton = document.getElementById('removeFeaturedImage');

let selectedImageUrl = null;

imageInput.addEventListener('change', function () {

    const file = this.files[0];

    if (!file) return;

    const allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!allowedTypes.includes(file.type)) {
        alert('শুধু JPG, PNG অথবা WEBP ছবি নির্বাচন করুন।');
        this.value = '';
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert('ছবির সাইজ সর্বোচ্চ 2 MB হতে পারবে।');
        this.value = '';
        return;
    }

    if (selectedImageUrl) {
        URL.revokeObjectURL(selectedImageUrl);
    }

    selectedImageUrl = URL.createObjectURL(file);

    imagePreview.src = selectedImageUrl;
    imagePreview.style.display = 'block';
    imagePlaceholder.style.display = 'none';
    removeButton.style.display = 'inline-block';
});

removeButton.addEventListener('click', function () {

    imageInput.value = '';
    imagePreview.removeAttribute('src');
    imagePreview.style.display = 'none';
    imagePlaceholder.style.display = 'block';
    removeButton.style.display = 'none';

    if (selectedImageUrl) {
        URL.revokeObjectURL(selectedImageUrl);
        selectedImageUrl = null;
    }
});
</script>
 