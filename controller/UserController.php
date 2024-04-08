<?php
//import model
require 'model/UserModel.php';

// m = ten cua ham nam trong file controller trong thu muc controller
$m = trim($_GET['m'] ?? 'index'); // ham mac dinh trong controller ten la index
$m = strtolower($m); // viet thuong tat ca ten ham

switch ($m) {
    case 'index':
        index();
        break;
    case 'add':
        Add();
        break;
    case 'handle-add':
        handleAdd();
        break;
    case 'delete':
        handleDelete();
        break;
    case 'edit':
        edit();
        break;
    case 'handle-edit':
        handleEdit();
        break;
    default:
        index();
        break;
}
function index()
{
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $keyword = trim($_GET['search'] ?? null);
    $keyword = strip_tags($keyword);
    $page = trim($_GET['page'] ?? null);
    $page = (is_numeric($page) && $page > 0) ? $page : 1;
    $linkPage = createLink([
        'c' => 'user',
        'm' => 'index',
        'page' => '{page}',
        'search' => $keyword,
    ]);
    $totalItems = getAllDataUser($keyword); // goi ten ham trong model
    $totalItems = count($totalItems);
    $user = [];
     
    // departments
    $pagigate = pagigate($linkPage, $totalItems, $page, $keyword, 3);
    $start = $pagigate['start'] ?? 0;
    $user = getAllDataUserByPage($keyword, $start, 3);
    $htmlPage = $pagigate['pagination'] ?? null;
    require 'view/user/index_view.php';
}
function handleEdit()
{
    if (isset($_POST['btnSave'])) {
        $id = trim($_GET['id'] ?? null);
        $id = is_numeric($id) ? $id : 0;
        $info = getDetailUserById($id);

        $full_name = trim($_POST['full_name'] ?? null);
        $full_name = strip_tags($full_name);

        $extra_code = trim($_POST['extra_code'] ?? null);
        $extra_code = strip_tags($extra_code);

        $email = trim($_POST['email'] ?? null);
        $email = strip_tags($email);

        $phone = trim($_POST['phone'] ?? null);
        $phone = strip_tags($phone);

        $address = trim($_POST['address'] ?? null);
        $address = strip_tags($address);

        $gender = trim($_POST['gender'] ?? null);
        $gender = strip_tags($gender);

        $role_id = trim($_POST['role_id'] ?? null);
        $role_id = $role_id;

        $date_of_birth = trim($_POST['birthday'] ?? null);
        $date_of_birth = date('Y-m-d', strtotime($date_of_birth));

        // kiem tra thong tin
        $_SESSION['error_update_user'] = [];
        if (empty($full_name)) {
            $_SESSION['error_update_user']['full_name'] = 'Enter name of course, please';
        } elseif (!preg_match('/^[a-zA-Z ]+$/', $full_name)) {
            $_SESSION['error_update_user']['full_name'] = 'Invalid fname format, please enter alphabetic characters only';
        } else {
            $_SESSION['error_update_courses']['full_name'] = null;
        }
        if (empty($extra_code)) {
            $_SESSION['error_update_user']['extra_code'] = 'Enter extra code of user, please';
        } else {
            $_SESSION['error_update_user']['extra_code'] = null;
        }
        if (empty($email)) {
            $_SESSION['error_update_user']['email'] = 'Enter email of user, please';
        } else {
            $_SESSION['error_update_user']['email'] = null;
        }
        if (empty($phone)) {
            $_SESSION['error_update_user']['phone'] = 'Enter phone of user, please';
        } elseif (!preg_match('/^\d{10}$/', $phone)) {
            $_SESSION['error_update_user']['phone'] = 'Phone number must be exactly 10 digits';
        } else {
            $_SESSION['error_update_user']['phone'] = null;
        }
        if (empty($address)) {
            $_SESSION['error_update_user']['address'] = 'Enter address of user, please';
        } else {
            $_SESSION['error_update_user']['address'] = null;
        }
        if (empty($date_of_birth)) {
            $_SESSION['error_update_user']['birthday'] = 'Enter date of birth of user, please';
        } else {
            $_SESSION['error_update_user']['birthday'] = null;
        }
        $logo = $info['avatar'] ?? null;
        $_SESSION['error_add_user']['avatar'] = null;
        if(!empty($_FILES['avatar']['tmp_name'])){
            $avatar = uploadFile(
                $_FILES['avatar'],
                'public/uploads/images/',
                ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'],
                5*1024*1024
            );
            if(empty($logo)){
                $_SESSION['error_add_user']['avatar'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 5Mb';
            } else {
                $_SESSION['error_add_user']['avatar'] = null;
            }
        }
        

        $flagCheckingError = false;
        foreach ($_SESSION['error_update_user'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }
        if (!$flagCheckingError) {
            // khong co loi - insert du lieu vao database
            if (isset($_SESSION['error_update_user'])) {
                unset($_SESSION['error_update_user']);
            }
            $data = [
                'full_name' => $full_name,
                'extra_code' => $extra_code,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'gender' => $gender,
                'role_id' => $role_id,
                'birthday' => formatDate($date_of_birth, 'Y-m-d'),
                'status' => 1,
                'avatar' => $avatar,
                'id' => $id,
            ];
            $update = updateUserById($data);
            if ($update) {
                // update thanh cong
                header("Location:index.php?c=user&state=success");
            } else {
                header("Location:index.php?c=user&m=edit&id={$id}&state=error");
            }
        } else {
            // co loi - quay lai form
            header("Location:index.php?c=user&m=edit&id={$id}&state=failure");
        }
    }
}
function edit()
{
    // phai dang nhap moi duoc su dung chuc nang nay.
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0; // is_numeric : kiem tra co phai la so hay ko ?
    $info = getDetailUserById($id); // goi ham trong model
    if (!empty($info)) {
        // co du lieu trong database
        // hien thi giao dien - thong tin chi tiet du lieu
        require 'view/user/edit_view.php';
    } else {
        // khong co du lieu trong database
        // thong bao 1 giao dien loi
        require 'view/error_view.php';
    }
}
function handleDelete()
{
    // phai dang nhap moi duoc su dung chuc nang nay.
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0;
    $delete = deleteUserById($id); // goi ten ham trong model
    if ($delete) {
        // xoa thanh cong
        header("Location:index.php?c=user&state_del=success");
    } else {
        // xoa that bai
        header("Location:index.php?c=user&state_del=failure");
    }
}
function handleAdd()
{
    if (isset($_POST['btnSave'])) {

        $full_name = trim($_POST['full_name'] ?? null);
        $full_name = strip_tags($full_name);

        $extra_code = trim($_POST['extra_code'] ?? null);
        $extra_code = strip_tags($extra_code);

        $email = trim($_POST['email'] ?? null);
        $email = strip_tags($email);

        $phone = trim($_POST['phone'] ?? null);
        $phone = strip_tags($phone);

        $address = trim($_POST['address'] ?? null);
        $address = strip_tags($address);

        $gender = trim($_POST['gender'] ?? null);
        $gender = $gender === '0' || $gender === '1' ? $gender : 0;

        $role_id = trim($_POST['role_id'] ?? null);
        $role_id = $role_id === '2' || $role_id === '3' ? $role_id : 0;

        $date_of_birth = trim($_POST['birthday'] ?? null);
        $date_of_birth = date('Y-m-d', strtotime($date_of_birth));

        // kiem tra thong tin
        $_SESSION['error_add_user'] = [];
        if (empty($full_name)) {
            $_SESSION['error_add_user']['full_name'] = 'Enter full name of user, please';
        } elseif (!preg_match('/^[a-zA-Z ]+$/', $full_name)) {
            $_SESSION['error_add_user']['full_name'] = 'Invalid full name format, please enter alphabetic characters only';
        } else {
            $_SESSION['error_add_user']['full_name'] = null;
        }
        
        if (empty($extra_code)) {
            $_SESSION['error_add_user']['extra_code'] = 'Enter extra code of user, please';
        } else {
            $_SESSION['error_add_user']['extra_code'] = null;
        }
        
        if (empty($email)) {
            $_SESSION['error_add_user']['email'] = 'Enter email of user, please';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_add_user']['email'] = 'Invalid email format';
        } else {
            $_SESSION['error_add_user']['email'] = null;
        }
        
        if (empty($phone)) {
            $_SESSION['error_add_user']['phone'] = 'Enter phone of user, please';
        } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
            $_SESSION['error_add_user']['phone'] = 'Invalid phone format, please enter 10 digits only';
        } else {
            $_SESSION['error_add_user']['phone'] = null;
        }
        
        if (empty($address)) {
            $_SESSION['error_add_user']['address'] = 'Enter address of user, please';
        } else {
            $_SESSION['error_add_user']['address'] = null;
        }
        
        if (empty($date_of_birth)) {
            $_SESSION['error_add_user']['birthday'] = 'Enter date of birth of user, please';
        } else {
            $_SESSION['error_add_user']['birthday'] = null;
        }
        $avatar = null;
        $_SESSION['error_add_user']['avatar'] = null;
        if(!empty($_FILES['avatar']['tmp_name'])){
            $avatar = uploadFile(
                $_FILES['avatar'],
                'public/uploads/images/',
                ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'],
                5*1024*1024
            );
            if(empty($avatar)){
                $_SESSION['error_add_department']['avatar'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 5Mb';
            } else {
                $_SESSION['error_add_department']['avatar'] = null;
            }
        }

        $flagCheckingError = false;
        foreach ($_SESSION['error_add_user'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }

        if (!$flagCheckingError) {
            // tien hanh insert vao database
            $data = [
                'full_name' => $full_name,
                'extra_code' => $extra_code,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'gender' => $gender,
                'role_id' => $role_id,
                'birthday' => formatDate($date_of_birth, 'Y-m-d'),
                'status' => 1,
                'avatar' => $avatar
            ];

            $insert = insertUser($data);
            if ($insert) {
                header("Location:index.php?c=user&state=success");
            } else {
                header("Location:index.php?c=user&m=add&state=error");
            }
        } else {
            // thong bao loi cho nguoi dung biet
            header("Location:index.php?c=user&m=add&state=fail");
        }
    }
}
function Add()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $department_id = $_POST['department_id'];
        if (empty($department_id)) {
            echo 'Please choose a department';
        } else {
            // Xử lý khi phòng ban được chọn
        }
    }
    require 'view/user/add_view.php';
}