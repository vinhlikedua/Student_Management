<?php
if (!defined('ROOT_PATH')) {
    die('Can not access');
}
$titlePage = "Btec - Update User";
$errorUpdate  = $_SESSION['error_update_user'] ?? null;
?>
<?php require 'view/partials/header_view.php'; ?>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Update User
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <a class="btn btn-primary" href="index.php?c=user&m=index"> User List</a>
        <div class="card mt-3">
            <div class="card-header bg-primary">
                <h5 class="card-title text-white"> Update User</h5>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" method="post"
                    action="index.php?c=user&m=handle-edit&id=<?= $info['id']; ?>">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group mb-3">
                                <label>Full Name</label>
                                <input value="<?= $info['full_name']; ?>" class="form-control" type="text"
                                    name="full_name" pattern="[a-zA-Z\s]+" required />
                                <?php if(!empty($errorAdd['full_name'])): ?>
                                <span class="text-danger"><?= $errorAdd['full_name']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input value="<?= $info['email']; ?>" class="form-control" type="email" name="email" />
                                <?php if(!empty($errorAdd['email'])): ?>
                                <span class="text-danger"><?= $errorAdd['email']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone">Phone Number</label>
                                <input value="<?= htmlspecialchars($info['phone']); ?>" class="form-control" type="text"
                                    name="phone" id="phone" pattern="[0-9]{10}"
                                    title="Phone number must be exactly 10 digits and only number" required />
                                <?php if(!empty($errorAdd['phone'])): ?>
                                <span class="text-danger"><?= htmlspecialchars($errorAdd['phone']); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Address</label>
                                <input value="<?= $info['address']; ?>" class="form-control" type="text"
                                    name="address" />
                                <?php if(!empty($errorAdd['address'])): ?>
                                <span class="text-danger"><?= $errorAdd['address']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>




                        <div class="col-sm-12 col-md-6">
                            <div class="form-group mb-3">
                                <label>Extra Code</label>
                                <input value="<?= $info['extra_code']; ?>" class="form-control" type="text"
                                    name="extra_code" />
                                <?php if(!empty($errorAdd['extra_code'])): ?>
                                <span class="text-danger"><?= $errorAdd['extra_code']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Role</label>
                                <select class="form-control" name="role_id">
                                    <option <?= $info['role_id'] == 2 ? 'selected' : '' ?> value="2">Student</option>
                                    <option <?= $info['role_id'] == 3 ? 'selected' : '' ?> value="3">Teacher</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Date Of Birth</label>
                                <input type="date" class="form-control" name="birthday"
                                    value="<?= $info['birthday']; ?>" />
                                <?php if(!empty($errorAdd['birthday'])): ?>
                                <span class="text-danger"><?= $errorAdd['birthday']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Gender</label>
                                <input type="radio" value="0" name="gender"
                                    <?= $info['gender'] == 0 ? 'checked' : '' ?> /> Male
                                <input type="radio" value="1" name="gender"
                                    <?= $info['gender'] == 1 ? 'checked' : '' ?> /> Femane
                            </div>
                            <div class="form-group mb-3">
                                <label> avatar</label>
                                <input type="file" class="form-control" name="avatar" />
                                <?php if(!empty($errorUpdate['logo'])): ?>
                                <span class="text-danger"><?= $errorUpdate['avatar']; ?></span>
                                <?php endif; ?>
                                <br />
                                <img width="50%" class="img-fluid" alt="<?= $info['avatar']; ?>"
                                    src="public/uploads/images/<?= $info['avatar']; ?>" />
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" name="btnSave">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'view/partials/footer_view.php'; ?>