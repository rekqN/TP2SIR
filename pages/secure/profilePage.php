<?php
    require_once __DIR__ . '/../../middleware/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
?>

<style>
    .empty-avatar {
        width: 0;
        height: 0; 
        background-color: #f0f0f0; 
    }
</style>

<?php include __DIR__ . '/sidebar.php'; ?>
<link rel="icon" href="../../landingPage/assets/images/icon-1.png" type="image/x-icon">
    <div class="p-4 overflow-auto h-100">
        <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item">Profile</li>
            </ol>
        </nav>
    
        <section class="py-4 px-5">
            <?php
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                    echo $_SESSION['success'] . '<br>';
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                    unset($_SESSION['success']);
                }
                if (isset($_SESSION['errors'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                    foreach ($_SESSION['errors'] as $error) {
                        echo $error . '<br>';
                    }
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    unset($_SESSION['errors']);
                }
            ?>
        </section>

        <div class="d-flex justify-content-center">
            <button class="btn btn-brown mx-2 my-0" onclick="showProfile()">Profile</button>
            <button class="btn btn-brown mx-2 my-0" onclick="showChangePassword()">Password</button>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-user-modal-<?= $user['userID']; ?>">Delete</button>
        </div>

        <div class="row mt-5">
            <div class="col-md-4 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <?php if (!empty($user['avatar'])): ?>
                        <?php
                            $avatarData = base64_decode($user['avatar']);
                            $avatarSrc = 'data:image/jpeg;base64,' . base64_encode($avatarData);
                        ?>
                        <div class="h-auto w-100">
                            <img src="<?= $avatarSrc ?>" alt="avatar" class="object-fit-cover w-100 img-fluid d-block ui-w-80 mx-auto rounded" style="max-width: 100px;">
                        </div>
                    <?php else: ?>
                    <?php endif; ?>   
                    <form action="../../controllers/user/updateAvatar.php" method="post" enctype="multipart/form-data">
                        <label class="btn btn-brown mt-2">Choose<input type="file" class="account-settings-fileinput d-none" name="avatar">
                        </label>
                        <button type="submit" class="btn btn-brown px-3 mt-2" name="update_avatar">Upload</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8 mb-5">
                <div class="mb-5" id="profileSection">
                    <form action="../../controllers/user/updateUser.php" method="post">
                        <div class="row">
                            <div class="col-auto form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control mb-1" name="firstName" value="<?= $user['firstName'] ?>">
                            </div>
                            <div class="col-auto form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control mb-1" name="lastName" value="<?= $user['lastName'] ?>">
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label">Email Address</label>
                            <input type="text" class="form-control mb-1" name="emailAddress" value="<?= $user['emailAddress'] ?>" style="max-width: 495px;">
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label">Birth Date</label>
                            <input type="date" class="form-control mb-1" name="dateOfBirth" value="<?= $user['dateOfBirth'] ?>" style="max-width: 495px;">
                        </div>
                        <div class="text-right mt-2 mb-2">
                            <button type="submit" class="btn btn-brown">Save changes</button>&nbsp;
                            <button type="button" class="btn btn-danger" onclick="refreshPage()">Cancel</button>
                        </div>
                    </form>
                </div>

                <div class="mt-4" id="passwordSection" style="display: none;">
                    <form action="../../controllers/user/updatePassword.php" method="post">
                        <div class="form-group">
                            <label class="form-label">Current password</label>
                            <input type="password" class="form-control" name="currentPassword" required style="max-width: 495px;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">New password</label>
                            <input type="password" class="form-control" name="newPassword" required style="max-width: 495px;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Repeat new password</label>
                            <input type="password" class="form-control" name="confirmNewPassword" required style="max-width: 495px;">
                        </div>

                        <div class="text-right my-3 ">
                            <button type="submit" class="btn btn-brown">Save changes</button>&nbsp;
                            <button type="button" class="btn btn-danger" onclick="refreshPage()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-user-modal-<?= $user['userID']; ?>" tabindex="-1" aria-labelledby="delete-user-modal-<?= $user['userID']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../controllers/auth/signin.php" method="post">
                        <input type="hidden" name="userID" value="<?= $user['userID']; ?>">
                        <div class="mb-3">
                            Do you want to delete your account?
                        </div>
                        <button type="submit" name="user" value="delete" class="btn btn-danger ">Yes, I want to delete my account.</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    function showChangePassword() {
        document.getElementById('profileSection').style.display = 'none';
        document.getElementById('passwordSection').style.display = 'block';
    }

    function showProfile() {
        document.getElementById('passwordSection').style.display = 'none';
        document.getElementById('profileSection').style.display = 'block';
    }
    function refreshPage() {
        location.reload(true);
    }
</script>