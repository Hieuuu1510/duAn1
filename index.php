<?php
session_start();
include 'model/pdo.php';
include 'model/danhmuc.php';
include 'model/number_home.php';
include 'model/admins.php';
include 'model/sanpham.php';
include 'model/khachhang.php';
include 'model/phan_hoi.php';
include 'model/tin_tuc.php';
include 'model/binhluan.php';
include 'model/cart_func.php';


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
            // controller Loại sản phẩm
        case 'loai_san_pham':
            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            $list_dm = loadall_danhmuc();
            include 'admin/loai_san_pham/loai_san_pham.php';
            break;
            //  Thêm sản phẩm danh mục
        case 'add_dm':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            if (isset($_POST['btn_dm'])) {
                $list_dm = loadall_danhmuc();
                $category = $_POST['category'];
                $allupload = true;
                foreach ($list_dm as $key => $row) {
                    if ($category == $row['name'] || $category == "") {
                        $error_dm = "Tên danh mục đã tồn tại hoặc bị để trống";
                        $allupload = false;
                    }
                }

                if ($allupload == true) {
                    add_danhmuc($category);
                }
            }
            $list_dm = loadall_danhmuc();
            include 'admin/loai_san_pham/loai_san_pham.php';
            break;
            // Xoá sản phẩm danh mục
        case 'xoadm':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                delete_danhmuc($id);
                delete_sanpham_danhmuc($id);
                $list_dm = loadall_danhmuc();
            }
            include 'admin/loai_san_pham/loai_san_pham.php';
            break;
            // Lấy dữ liệu của phần tử muốn sửa
        case 'suadm':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }
            unset($_SESSION['error_dm']);
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $listone_danhmuc = loadone_danhmuc($id);
            }
            include 'admin/loai_san_pham/update.php';
            break;
            // cập nhật dữ liệu mới
        case 'update_dm':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            if (isset($_POST['update_dm'])) {

                $category = $_POST['category'];
                $id = $_POST['id'];
                $list_dm = loadall_danhmuc();
                $allupload = true;
                // foreach ($list_dm as $key => $row) {
                //     if($category == $row['name'] || $category == ""){
                //         // $_SESSION['error_dm'] = "Tên danh mục đã tồn tại hoặc bị để trống";
                //         $error_dm = "Tên danh mục đã tồn tại hoặc bị đển trống";
                //         $allupload = false;
                //     }
                //     else{
                //         unset($_SESSION['error_dm']);
                //     }
                // }
                if ($allupload == true) {
                    update_danhmuc($id, $category);
                }
                if ($allupload == false) {
                    header('location: index.php?action=suadm&id=' . $id);
                    $_SESSION['error_dm'] = $error_dm;
                }
            }
            $list_dm = loadall_danhmuc();
            include 'admin/loai_san_pham/loai_san_pham.php';
            break;

            // controller Sản phẩm
            // Thêm sản phẩm
        case 'add_sp':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            if (isset($_POST['add_sp'])) {
                $iddm = $_POST['name_dm'];
                $name = $_POST['name'];
                $price = $_POST['price'];
                $details = $_POST['details'];

                $img = $_FILES['image']['name'];
                $target_dir = "uploaded_img/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

                $img2 = $_FILES['image2']['name'];
                $target_dir = "uploaded_img/";
                $target_file = $target_dir . basename($_FILES["image2"]["name"]);
                move_uploaded_file($_FILES["image2"]["tmp_name"], $target_file);

                $img3 = $_FILES['image3']['name'];
                $target_dir = "uploaded_img/";
                $target_file = $target_dir . basename($_FILES["image3"]["name"]);
                move_uploaded_file($_FILES["image3"]["tmp_name"], $target_file);

                // bắt lỗi thêm sản phẩm
                $upload = true;
                $list_sp = loadall_sanpham(0);
                if ($iddm == "") {
                    $error_iddm = "id danh mục không được để trống";
                    $upload = false;
                }

                foreach ($list_sp as $key => $row) {
                    if ($name == "") {
                        $error_name = "tên sản phầm không được để trống";
                        $upload = false;
                    }
                    if ($name == $row['name']) {
                        $error_name = "Tên sản phẩm đã tồn tại";
                        $upload = false;
                    }
                }
                if ($price == "") {
                    $error_price = "Giá không được để trống hoặc bắt buộc phải nhập số";
                    $upload = false;
                }

                if ($img == "" || $img2 == "" || $img3 == "") {
                    $error_img = "Ảnh không được để trống";
                    $upload = false;
                }

                if ($details == "") {
                    $error_detail = "chi tiết sản phẩm không được để trống";
                    $upload = false;
                }

                if ($upload == true) {
                    add_sanpham($name, $price, $img, $img2, $img3, $details, $iddm);
                    header('location: index.php?action=list_sp');
                }
            }
            $list_dm = loadall_danhmuc();

            include 'admin/hang_hoa/add.php';
            break;
            // select sản phẩm
        case 'list_sp':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            $list_sp = loadall_sanpham(0);
            include 'admin/hang_hoa/list.php';
            break;
            // xoá sản phẩm
        case 'xoasp':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            if (isset($_GET['id_sp'])) {
                $id_sp = $_GET['id_sp'];
                delete_sanpham($id_sp);
                $list_sp = loadall_sanpham(0);
            } else {
                include 'admin/hang_hoa/list.php';
            }
            include 'admin/hang_hoa/list.php';
            break;
            // lấy dữ liệu sản phẩm cần update
        case 'suasp':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            if (isset($_GET['id_sp'])) {
                $id_sp = $_GET['id_sp'];
                $listone_sanpham = loadone_sanpham($id_sp);
            }
            include 'admin/hang_hoa/update.php';
            break;
            // Update sản phẩm
        case 'update_sp':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            if (isset($_POST['update_sp'])) {
                // $iddm = $_POST['name_dm'];
                $name = $_POST['name'];
                $price = $_POST['price'];
                $details = $_POST['details'];
                $id_sp = $_POST['id_sp'];

                $img = $_FILES['image']['name'];
                $target_dir = "uploaded_img/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

                $img2 = $_FILES['image2']['name'];
                $target_dir = "uploaded_img/";
                $target_file = $target_dir . basename($_FILES["image2"]["name"]);
                move_uploaded_file($_FILES["image2"]["tmp_name"], $target_file);

                $img3 = $_FILES['image3']['name'];
                $target_dir = "uploaded_img/";
                $target_file = $target_dir . basename($_FILES["image3"]["name"]);
                move_uploaded_file($_FILES["image3"]["tmp_name"], $target_file);

                update_sanpham($name, $price, $img, $img2, $img3, $details, $id_sp);
            }
            $list_sp = loadall_sanpham(0);
            include 'admin/hang_hoa/list.php';
            break;

            // Controller tài khoản admins
        case 'login_admin':
            if (isset($_POST['login_admin'])) {
                $thongbao = '';
                $user = $_POST['name'];
                $pass = $_POST['pass'];
                $check_admins = check_admins($user, $pass);
                if (is_array($check_admins)) {
                    $_SESSION['admin'] = $check_admins;
                    header('location:index.php');
                    // include 'index.php';
                } else {
                    $thongbao = "Tên đăng nhập hoặc mật khẩu không đúng !";
                    include 'admin/account/login_admin.php';
                }
            } else {
                include 'admin/account/login_admin.php';
            }
            break;
        case 'logout_admin':
            unset($_SESSION['admin']);
            include 'admin/account/login_admin.php';
            break;
        case 'register_admin':
            $thongbao = '';
            $thanhcong = '';
            $error = array();
            if (isset($_POST['add_admins'])) {
                $user = $_POST['username'];
                $pass = $_POST['pass'];
                $cpass = $_POST['cpass'];
                $same_name = select_admin_where_name($user);

                if (empty($user)) {
                    $error['username'] = "Tên đăng nhập không được trống !";
                } else if ($same_name > 0) {
                    $error['username'] = "Tên đăng nhập đã tồn tại !";
                }

                if (empty($pass)) {
                    $error['pass'] = "Mật khẩu không được trống !";
                }

                if (empty($cpass)) {
                    $error['cpass']  = "Hãy xác nhận mật khẩu !";
                } else if ($pass != $cpass) {
                    $error['cpass'] = "Mật khẩu không trùng khớp !";
                }

                if (empty($error)) {
                    add_admins($user, $cpass);
                    $thanhcong = "Đăng ký thành công !";
                }
            }
            include 'admin/account/register_admin.php';
            break;
            // contrller khách hàng
        case 'list_khachhang':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            $list_khachhang = loadall_khachhang();
            include 'admin/nguoi_dung/list_khachhang.php';
            break;
        case 'xoa_kh':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
                // include 'admin/account/login_admin.php';
            }

            if (isset($_GET['id_kh'])) {
                $id_kh = $_GET['id_kh'];
                delete_khachhang($id_kh);
            }
            $list_khachhang = loadall_khachhang();
            include 'admin/nguoi_dung/list_khachhang.php';
            break;

        case 'sua_kh':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
            }
            if(isset($_GET['id_kh'])){
                $id_kh = $_GET['id_kh'];
                $list_one_kh = loadone_khachhang($id_kh);
            }
            include 'admin/nguoi_dung/update.php';
            break;
        case 'update_kh':

            if (!isset($_SESSION['admin'])) {
                header('location:index.php?action=login_admin');
            }
            if(isset($_POST['update_kh'])){
                $name = $_POST['name'];
                $pass = $_POST['pass'];
                $address = $_POST['address'];
                $email = $_POST['email'];
                $id_kh = $_POST['id_kh'];   
                update_khachhang($name,$pass,$address,$email,$id_kh);
            }
            $list_khachhang = loadall_khachhang();
            include 'admin/nguoi_dung/list_khachhang.php';
            break;
    
            // Controller news
        case 'list_news':
            $list_news = show_news();
            include 'admin/tin_tuc/show_news.php';
            break;
        case 'add_news':
            $error = array();
            if (isset($_POST['add_news'])) {


                $image = $_FILES['image']['name'];
                $image_tmp_name = $_FILES['image']['tmp_name'];
                $image_folder = "uploaded_img/" . $image;


                if (empty($_POST['title'])) {
                    $error['title'] = 'Tiêu đề không được trống';
                } else {
                    $title = $_POST['title'];
                }

                if (empty($_POST['content'])) {
                    $error['content'] = 'Mô tả không được trống';
                } else {
                    $content = $_POST['content'];
                }

                if (empty($image)) {
                    $error['image'] = 'File ảnh không được trống';
                }

                if (empty($error)) {
                    insert_news($title, $image, $content);
                    move_uploaded_file($image_tmp_name, $image_folder);
                    header('location:index.php?action=list_news');
                }
            }
            include 'admin/tin_tuc/add_news.php';
            break;
        case 'edit_news':
            $error = array();
            $edit_id = $_GET['edit'];
            $fetch_new = select_news($edit_id);
            if (isset($_POST['edit_news'])) {

                $new_id = $_POST['new_id'];

                $old_image = $_POST['image_old'];
                $image = $_FILES['image']['name'];
                $image_size = $_FILES['image']['size'];
                $image_tmp_name = $_FILES['image']['tmp_name'];
                $image_folder = "uploaded_img/" . $image;


                if (empty($_POST['title'])) {
                    $error['title'] = 'Tiêu đề không được trống';
                } else {
                    $title = $_POST['title'];
                }

                if (empty($_POST['content'])) {
                    $error['content'] = 'Mô tả không được trống';
                } else {
                    $content = $_POST['content'];
                }

                if (empty($error['title']) && empty($error['content'])) {
                    update_title_and_content($title, $content, $edit_id);
                    header('location:index.php?action=list_news');
                }
                if (!empty($image)) {
                    if ($image_size > 2000000) {
                        $error['image'] = "FILE ảnh quá lớn";
                    } else {
                        update_image($image, $edit_id);
                        move_uploaded_file($image_tmp_name, $image_folder);
                        unlink('uploaded_img/' . $old_image);
                    }
                    header('location:index.php?action=list_news');
                }
            }
            include 'admin/tin_tuc/edit_news.php';
            break;
        case 'delete_new':
            $delete_id = $_GET['delete'];
            delete_new($delete_id);
            $list_news = show_news();
            include 'admin/tin_tuc/show_news.php';
            break;
            // Controller phản hồi
        case 'show_messages':
            $list_messages = show_message();
            include 'admin/phan_hoi/show_message.php';
            break;
        case 'delete_phanhoi':
            if (isset($_GET['id_phanhoi'])) {
                $delete_id = $_GET['id_phanhoi'];
                delete_message($delete_id);
            }
            $list_messages = show_message();
            include 'admin/phan_hoi/show_message.php';
            break;
            // Controller bình luận
        case 'show_binhluan':
            $list_bl = loadall_binhluan(0);
            include 'admin/binh_luan/show_comment.php';
            break;
        case 'xoabl':
            if (isset($_GET['id_bl'])) {
                $id_bl = $_GET['id_bl'];
                delete_binhluan($id_bl);
            }
            $list_bl = loadall_binhluan(0);
            include 'admin/binh_luan/show_comment.php';
            break;
            // Controller Đơn hàng
        case 'order_admin':
            
            if (isset($_POST['update_payment'])) {
                $order_id = $_POST['order_id'];
                $payment_status = $_POST['payment_status'];
                update_payment($payment_status, $order_id);
                // var_dump($payment_status);
                // die();
            }

            if (isset($_GET['order_code'])) {
                $delete_id = $_GET['order_code'];
                delete_order($delete_id);
                delete_order_product($delete_id);
                delete_order_vnpay($delete_id);
                delete_order_momo($delete_id);
                
            }
            include 'admin/don_hang/order_admin.php';
            break;
        case 'order_pending':
            if (isset($_POST['update_payment'])) {
                $order_id = $_POST['order_id'];
                $payment_status = $_POST['payment_status'];
                update_payment($payment_status, $order_id);
            }

            if (isset($_GET['delete'])) {
                $delete_id = $_GET['delete'];
                delete_order($delete_id);
                delete_order_product($delete_id);
            }
            include 'admin/don_hang/pending_order.php';
            break;
        case 'order_completed':
            if (isset($_POST['update_payment'])) {
                $order_id = $_POST['order_id'];
                $payment_status = $_POST['payment_status'];
                update_payment($payment_status, $order_id);
            }

            if (isset($_GET['delete'])) {
                $delete_id = $_GET['delete'];
                delete_order($delete_id);
                delete_order_product($delete_id);
            }
            include 'admin/don_hang/completed_order.php';
            break;
        case 'order_product':
            include 'admin/don_hang/order_product.php';
            break;
        default:
            include 'views/home_admin.php';
            break;
    }
} else {
    if (isset($_SESSION['admin'])) {
        // header('location:index.php');
        include 'views/home_admin.php';
    } else {
        // header('location:index.php?action=login_admin');
        include 'admin/account/login_admin.php';
    }
}
