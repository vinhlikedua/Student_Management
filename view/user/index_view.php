<?php
if (!defined('ROOT_PATH')) {
    die('Can not access');
}
$titlePage = "Btec - User";
$state = trim($_GET['state'] ?? null);
?>
<?php require 'view/partials/header_view.php'; ?>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-contacts"></i>
        </span> User
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
        <a class="btn btn-primary btn-sm" href="index.php?c=user&m=add"> Add New User</a>

        <div class="row mt-3">
            <div class="col-sm-12 col-md-6">
                <input type="text" id="keyword" value="<?= htmlentities($keyword); ?>" />
                <button id="btnSearchDepartment" class="btn btn-primary btn-sm">Search</button>
                <a class="btn btn-info btn-sm" href="index.php?c=user"> Refresh</a>
            </div>
        </div>

        <?php if($state === 'success'): ?>
        <div class="my-3 text-success text-center">
            Action Successfully !
        </div>
        <?php endif; ?>
        <table class="table table-bordered table-striped my-3">
            <thead class="table-primary">
                <th>id</th>
                <th>Full Namel</th>
                <th>Extracode</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>

                <th>avatar</th>
                <th>Gender</th>

                <th colspan="2" class="text-center" width="10%">Action</th>
            </thead>
            <tbody>
                <?php foreach($user as $key => $item): ?>
                <tr>
                    <td><?= $item['id']; ?></td>
                    <td><?= $item['full_name']; ?></td>
                    <td><?= $item['extra_code']; ?></td>
                    <td><?= $item['email']; ?></td>
                    <td><?= $item['role_id'] == 2 ? 'Student' : 'Teacher'; ?></td>
                    <td><?= $item['phone']; ?></td>
                    <td><?= $item['birthday']; ?></td>
                    <td width="10%">
                        <img style="width: 100%; height: 100%;" class="img-fluid" alt="<?= $item['avatar'] ?>"
                            src="public/uploads/images/<?= $item['avatar']; ?>" />
                    </td>
                    <td><?= $item['gender'] == 1 ? 'Male' : 'Female'; ?></td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="index.php?c=user&m=edit&id=<?= $item['id']; ?>">
                            Edit</a>
                    </td>
                    <td>
                        <a class="btn btn-danger btn-sm" href="index.php?c=user&m=delete&id=<?= $item['id']; ?>">
                            Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $htmlPage; ?>
    </div>
</div>

<?php require 'view/partials/footer_view.php'; ?>
<script>
let btnSearch = document.getElementById('btnSearchDepartment');
btnSearch.addEventListener('click', function() {
    let txtKeyword = document.getElementById('keyword');
    let keyword = txtKeyword.value.trim();
    if (keyword != '') {
        window.location.href = "index.php?c=user&search=" + keyword;
    } else {
        alert('Enter keyword, please');
    }
});
</script>