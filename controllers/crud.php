<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/dbConnection.php';

$conn = db();

/* -------------------------------
   BASIC CSRF PROTECTION
---------------------------------*/
function csrf_valid($token) {
    return isset($_SESSION['csrf_token']) &&
           hash_equals($_SESSION['csrf_token'], $token);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !csrf_valid($_POST['csrf_token'] ?? '')) {

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Request',
            text: 'Your session expired or the request is invalid.',
        }).then(() => {
            window.location.href = '../public/login.php';
        });
    </script>";
    exit;
}

$action = $_POST['action'] ?? '';
$user_id = intval($_POST['user_id'] ?? 0);

/* ======================================================
   ACTION HANDLER
========================================================*/
switch ($action) {


/* ======================================================
   1. APPROVE USER
========================================================*/
case 'approve_user':

    $stmt = $conn->prepare("UPDATE users SET account_status='Approved' WHERE user_id=?");
    $stmt->execute([$user_id]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'User Approved!',
        }).then(() => {
            window.location.href = '../admin/userManagement.php';
        });
    </script>";
    exit;



/* ======================================================
   2. REJECT USER
========================================================*/
case 'reject_user':

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=? AND account_status='For Approval'");
    $stmt->execute([$user_id]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'User Rejected'
        }).then(() => {
            window.location.href = '../admin/userManagement.php';
        });
    </script>";
    exit;



/* ======================================================
   3. ADD EQUIPMENT (UPDATED)
========================================================*/

case 'add_equipment':

    $name     = trim($_POST['item_name'] ?? '');
    $rfid     = trim($_POST['rfid_tag'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $qty      = intval($_POST['quantity'] ?? 0);
    $status   = trim($_POST['status'] ?? '');

    $stmt = $conn->prepare("
        INSERT INTO lab_equipments (item_name, rfid_tag, category, quantity, status)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([$name, $rfid, $category, $qty, $status]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Equipment Added'
        }).then(() => {
            window.location.href = '../admin/equipmentManagement.php';
        });
    </script>";
    exit;



/* ======================================================
   4. UPDATE EQUIPMENT (UPDATED)
========================================================*/
case 'update_equipment':

    $eid      = intval($_POST['equipment_id'] ?? 0);
    $name     = trim($_POST['item_name'] ?? '');
    $rfid     = trim($_POST['rfid_tag'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $qty      = intval($_POST['quantity'] ?? 0);
    $status   = trim($_POST['status'] ?? '');

    $stmt = $conn->prepare("
        UPDATE lab_equipments 
        SET item_name=?, rfid_tag=?, category=?, quantity=?, status=? 
        WHERE equipment_id=?
    ");

    $stmt->execute([$name, $rfid, $category, $qty, $status, $eid]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Updated Successfully',
        }).then(() => {
            window.location.href = '../admin/equipmentManagement.php';
        });
    </script>";
    exit;



/* ======================================================
   5. DELETE EQUIPMENT (UPDATED)
========================================================*/
case 'delete_equipment':

    $eid = intval($_POST['equipment_id'] ?? 0);

    $stmt = $conn->prepare("DELETE FROM lab_equipments WHERE equipment_id=?");
    $stmt->execute([$eid]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Equipment Deleted',
        }).then(() => {
            window.location.href = '../admin/equipmentManagement.php';
        });
    </script>";
    exit;


    /* ======================================================
   5. RETRIVE EQUIPMENT 
========================================================*/


/* ======================================================
   DEFAULT
========================================================*/
default:
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Unknown Action',
        }).then(() => {
            window.location.href = '../index.php';
        });
    </script>";
    exit;
}
