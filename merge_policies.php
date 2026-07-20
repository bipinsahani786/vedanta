<?php
$terms = file_get_contents('resources/views/terms.blade.php');
$pricing = file_get_contents('resources/views/pricing.blade.php');
$refund = file_get_contents('resources/views/refund.blade.php');

// Extract pricing content
preg_match('/<h2 class=\"text-2xl font-bold text-text-main mt-8 mb-4\">1\. Registration Plans<\/h2>(.*?)<div class=\"pt-8 mt-12/s', $pricing, $pricingMatch);
$pricingContent = isset($pricingMatch[1]) ? $pricingMatch[1] : '';
if (!$pricingContent) {
    preg_match('/<h2 class=\"text-2xl font-bold text-text-main mt-8 mb-4\">1\. Registration Plans<\/h2>(.*?)<\/div>\s*<\/div>\s*@endsection/s', $pricing, $pricingMatch);
    $pricingContent = $pricingMatch[1] ?? '';
}

// Extract refund content
preg_match('/<h2 class=\"text-2xl font-bold text-text-main mt-8 mb-4\">1\. Registration Fees<\/h2>(.*?)<h2 class=\"text-2xl font-bold text-text-main mt-8 mb-4\">10\. Contact Us<\/h2>/s', $refund, $refundMatch);
$refundContent = $refundMatch[1] ?? '';

// Format the appended content
$appendedContent = "
<h2 class=\"text-3xl font-bold text-text-main mt-12 mb-6 border-b pb-2\">Appendix A: Pricing & Service Charges Policy</h2>
<h3 class=\"text-2xl font-bold text-text-main mt-8 mb-4\">1. Registration Plans</h3>
" . $pricingContent . "

<h2 class=\"text-3xl font-bold text-text-main mt-12 mb-6 border-b pb-2\">Appendix B: Refund & Cancellation Policy</h2>
<h3 class=\"text-2xl font-bold text-text-main mt-8 mb-4\">1. Registration Fees</h3>
" . $refundContent . "
<h2 class=\"text-2xl font-bold text-text-main mt-8 mb-4\">10. Intellectual Property</h2>";

// Replace section 8 and 9 in terms with the appended content
$terms = preg_replace('/<h2 class=\"text-2xl font-bold text-text-main mt-8 mb-4\">8\. Payments & Service Charges<\/h2>.*?<h2 class=\"text-2xl font-bold text-text-main mt-8 mb-4\">10\. Intellectual Property<\/h2>/s', $appendedContent, $terms);

file_put_contents('resources/views/terms.blade.php', $terms);
echo 'Merged successfully.';
