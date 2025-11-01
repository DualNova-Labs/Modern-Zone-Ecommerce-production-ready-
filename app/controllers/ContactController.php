<?php
/**
 * Contact Controller
 */
class ContactController
{
    public function index()
    {
        $data = [
            'title' => 'Contact Us - Modern Zone Trading | Industrial Tools Supplier',
            'description' => 'Contact Modern Zone Trading for industrial tools and equipment. Located in Jeddah, Saudi Arabia. Call us at 012 6811 391 or email info@modernzonetrading.com',
            'success' => Request::get('success'),
        ];
        
        View::render('pages/contact', $data);
    }
    
    public function submit()
    {
        $name = Request::post('name');
        $email = Request::post('email');
        $subject = Request::post('subject');
        $message = Request::post('message');
        
        // Mock validation
        $errors = [];
        
        if (empty($name)) $errors['name'] = 'Name is required';
        if (empty($email)) $errors['email'] = 'Email is required';
        if (empty($subject)) $errors['subject'] = 'Subject is required';
        if (empty($message)) $errors['message'] = 'Message is required';
        
        if (empty($errors)) {
            // Send email or save to database
            header('Location: /host/mod/contact?success=1');
            exit;
        }
        
        $data = [
            'title' => 'Contact Us - Modern Zone Trading | Industrial Tools Supplier',
            'description' => 'Contact Modern Zone Trading for industrial tools and equipment. Located in Jeddah, Saudi Arabia.',
            'errors' => $errors,
            'old' => $_POST,
        ];
        
        View::render('pages/contact', $data);
    }
    
    public function support()
    {
        $data = [
            'title' => 'Support - Modern Zone Trading | Customer Service',
            'description' => 'Get support and answers to frequently asked questions about Modern Zone Trading products and services.',
            'faqs' => $this->getFaqs(),
        ];
        
        View::render('pages/support', $data);
    }
    
    private function getFaqs()
    {
        return [
            [
                'question' => 'What is your warranty policy?',
                'answer' => 'All our products come with a minimum 2-year warranty. Extended warranty options are available for purchase.',
            ],
            [
                'question' => 'Do you offer international shipping?',
                'answer' => 'Yes, we ship to most countries worldwide. Shipping costs and delivery times vary by location.',
            ],
            [
                'question' => 'How can I track my order?',
                'answer' => 'Once your order is shipped, you will receive a tracking number via email. You can use this to track your package.',
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept credit cards, debit cards, PayPal, and bank transfers.',
            ],
            [
                'question' => 'Can I return a product?',
                'answer' => 'Yes, we offer a 30-day return policy for unused products in original packaging.',
            ],
        ];
    }
}
