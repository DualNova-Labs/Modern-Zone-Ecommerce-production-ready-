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
        
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['error'] = 'Invalid security token. Please try again.';
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
            
            // Resize and save image
            if ($this->resizeAndSaveImage($_FILES['image']['tmp_name'], $targetPath, 1920, 600)) {
                $imagePath = 'public/assets/images/banners/' . $fileName;
            } else {
                error_log('Failed to process image: ' . $_FILES['image']['name']);
                $errors[] = 'Failed to process image. Please try a different image format.';
            }
        } else {
            if (isset($_FILES['image'])) {
                error_log('File upload error: ' . $_FILES['image']['error']);
            }
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
     * Get banner data for editing (AJAX endpoint)
     */
    public function edit($id)
    {
        header('Content-Type: application/json');
        
        $banner = $this->bannerModel->getBannerById($id);
        
        if (!$banner) {
            echo json_encode([
                'success' => false,
                'message' => 'Banner not found'
            ]);
            exit;
        }
        
        echo json_encode([
            'success' => true,
            'banner' => $banner
        ]);
        exit;
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
        
        // Validate CSRF token
        if (!$this->security->validateCsrfToken()) {
            $_SESSION['error'] = 'Invalid security token. Please try again.';
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
            
            // Resize and save image
            if ($this->resizeAndSaveImage($_FILES['image']['tmp_name'], $targetPath, 1920, 600)) {
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
    
    /**
     * Resize and save image to specified dimensions
     */
    private function resizeAndSaveImage($sourcePath, $targetPath, $targetWidth, $targetHeight)
    {
        // Check if GD extension is loaded
        if (!extension_loaded('gd')) {
            error_log('GD extension not loaded - falling back to simple file copy');
            return copy($sourcePath, $targetPath);
        }
        
        // Get image info
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            error_log('Could not get image info - falling back to simple file copy');
            return copy($sourcePath, $targetPath);
        }
        
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Create source image resource based on type
        $sourceImage = false;
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = @imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = @imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $sourceImage = @imagecreatefromwebp($sourcePath);
                }
                break;
            default:
                error_log('Unsupported image type: ' . $mimeType . ' - falling back to simple file copy');
                return copy($sourcePath, $targetPath);
        }
        
        if (!$sourceImage) {
            error_log('Could not create image resource - falling back to simple file copy');
            return copy($sourcePath, $targetPath);
        }
        
        // Calculate aspect ratios
        $sourceRatio = $sourceWidth / $sourceHeight;
        $targetRatio = $targetWidth / $targetHeight;
        
        // Calculate crop dimensions to maintain aspect ratio
        if ($sourceRatio > $targetRatio) {
            // Source is wider - crop width
            $cropWidth = $sourceHeight * $targetRatio;
            $cropHeight = $sourceHeight;
            $cropX = ($sourceWidth - $cropWidth) / 2;
            $cropY = 0;
        } else {
            // Source is taller - crop height
            $cropWidth = $sourceWidth;
            $cropHeight = $sourceWidth / $targetRatio;
            $cropX = 0;
            $cropY = ($sourceHeight - $cropHeight) / 2;
        }
        
        // Create target image
        $targetImage = @imagecreatetruecolor($targetWidth, $targetHeight);
        if (!$targetImage) {
            error_log('Could not create target image - falling back to simple file copy');
            imagedestroy($sourceImage);
            return copy($sourcePath, $targetPath);
        }
        
        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            @imagealphablending($targetImage, false);
            @imagesavealpha($targetImage, true);
            $transparent = @imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
            if ($transparent !== false) {
                @imagefill($targetImage, 0, 0, $transparent);
            }
        }
        
        // Resize and crop image
        $resampleResult = @imagecopyresampled(
            $targetImage, $sourceImage,
            0, 0, $cropX, $cropY,
            $targetWidth, $targetHeight, $cropWidth, $cropHeight
        );
        
        if (!$resampleResult) {
            error_log('Image resampling failed - falling back to simple file copy');
            imagedestroy($sourceImage);
            imagedestroy($targetImage);
            return copy($sourcePath, $targetPath);
        }
        
        // Save image as JPEG for better compression
        $success = @imagejpeg($targetImage, $targetPath, 90);
        
        // Clean up memory
        imagedestroy($sourceImage);
        imagedestroy($targetImage);
        
        if (!$success) {
            error_log('JPEG save failed - falling back to simple file copy');
            return copy($sourcePath, $targetPath);
        }
        
        return true;
    }
}
