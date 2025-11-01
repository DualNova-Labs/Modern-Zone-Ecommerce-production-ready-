<?php
/**
 * Admin Banner Controller
 * Manages hero slider banners from admin panel
 */
require_once APP_PATH . '/middleware/AdminMiddleware.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/View.php';
require_once APP_PATH . '/core/Request.php';
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/models/Banner.php';

class AdminBannerController
{
    private $bannerModel;
    private $security;
    
    public function __construct()
    {
        AdminMiddleware::check();
        $this->security = Security::getInstance();
        $this->bannerModel = new Banner();
    }
    
    /**
     * Display list of banners
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        $banners = $this->bannerModel->getAllBanners($perPage, $offset);
        $totalBanners = $this->bannerModel->countBanners();
        $totalPages = ceil($totalBanners / $perPage);
        
        $data = [
            'title' => 'Manage Banners',
            'banners' => $banners,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalBanners' => $totalBanners
        ];
        
        View::render('admin/banners/index', $data);
    }
    
    /**
     * Show create banner form
     */
    public function create()
    {
        $data = [
            'title' => 'Create New Banner'
        ];
        
        View::render('admin/banners/create', $data);
    }
    
    /**
     * Store new banner
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . View::url('admin/banners'));
            exit;
        }
        
        // Validate required fields
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = 'Title is required';
        }
        
        // Handle image upload
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/assets/images/banners/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = 'public/assets/images/banners/' . $fileName;
            } else {
                $errors[] = 'Failed to upload image';
            }
        } else {
            $errors[] = 'Banner image is required';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . View::url('admin/banners/create'));
            exit;
        }
        
        // Prepare data
        $data = [
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'badge' => $_POST['badge'] ?? '',
            'image' => $imagePath,
            'link_url' => $_POST['link_url'] ?? '',
            'link_text' => $_POST['link_text'] ?? 'Learn More',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'status' => $_POST['status'] ?? 'active'
        ];
        
        $bannerId = $this->bannerModel->createBanner($data);
        
        if ($bannerId) {
            $_SESSION['success'] = 'Banner created successfully!';
        } else {
            $_SESSION['error'] = 'Failed to create banner';
        }
        
        header('Location: ' . View::url('admin/banners'));
        exit;
    }
    
    /**
     * Show edit banner form
     */
    public function edit($id)
    {
        $banner = $this->bannerModel->getBannerById($id);
        
        if (!$banner) {
            $_SESSION['error'] = 'Banner not found';
            header('Location: ' . View::url('admin/banners'));
            exit;
        }
        
        $data = [
            'title' => 'Edit Banner',
            'banner' => $banner
        ];
        
        View::render('admin/banners/edit', $data);
    }
    
    /**
     * Update banner
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . View::url('admin/banners'));
            exit;
        }
        
        $banner = $this->bannerModel->getBannerById($id);
        
        if (!$banner) {
            $_SESSION['error'] = 'Banner not found';
            header('Location: ' . View::url('admin/banners'));
            exit;
        }
        
        // Validate required fields
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = 'Title is required';
        }
        
        // Handle image upload if new image provided
        $imagePath = $banner['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/assets/images/banners/';
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                // Delete old image
                $oldImagePath = ROOT_PATH . '/' . $banner['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                
                $imagePath = 'public/assets/images/banners/' . $fileName;
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . View::url('admin/banners/edit/' . $id));
            exit;
        }
        
        // Prepare data
        $data = [
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'badge' => $_POST['badge'] ?? '',
            'image' => $imagePath,
            'link_url' => $_POST['link_url'] ?? '',
            'link_text' => $_POST['link_text'] ?? 'Learn More',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'status' => $_POST['status'] ?? 'active'
        ];
        
        $success = $this->bannerModel->updateBanner($id, $data);
        
        if ($success) {
            $_SESSION['success'] = 'Banner updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update banner';
        }
        
        header('Location: ' . View::url('admin/banners'));
        exit;
    }
    
    /**
     * Delete banner
     */
    public function delete($id)
    {
        $banner = $this->bannerModel->getBannerById($id);
        
        if (!$banner) {
            $_SESSION['error'] = 'Banner not found';
            header('Location: ' . View::url('admin/banners'));
            exit;
        }
        
        // Delete banner image
        $imagePath = ROOT_PATH . '/' . $banner['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        
        $success = $this->bannerModel->deleteBanner($id);
        
        if ($success) {
            $_SESSION['success'] = 'Banner deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete banner';
        }
        
        header('Location: ' . View::url('admin/banners'));
        exit;
    }
    
    /**
     * Toggle banner status
     */
    public function toggleStatus($id)
    {
        $banner = $this->bannerModel->getBannerById($id);
        
        if (!$banner) {
            echo json_encode(['success' => false, 'message' => 'Banner not found']);
            exit;
        }
        
        $newStatus = $banner['status'] === 'active' ? 'inactive' : 'active';
        $success = $this->bannerModel->updateStatus($id, $newStatus);
        
        echo json_encode([
            'success' => $success,
            'status' => $newStatus,
            'message' => $success ? 'Status updated successfully' : 'Failed to update status'
        ]);
        exit;
    }
}
