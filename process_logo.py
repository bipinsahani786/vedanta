from PIL import Image, ImageChops
import sys

def trim_bg(im):
    # Convert to RGB to ignore alpha for white background detection
    rgb_im = im.convert('RGB')
    
    # Assume the top-left pixel is the background color (usually white or transparent)
    bg_color = rgb_im.getpixel((0,0))
    bg = Image.new('RGB', rgb_im.size, bg_color)
    
    diff = ImageChops.difference(rgb_im, bg)
    diff = ImageChops.add(diff, diff, 2.0, -100)
    bbox = diff.getbbox()
    
    if bbox:
        return im.crop(bbox)
    return im

def process_new_logo(input_path):
    try:
        img = Image.open(input_path)
        img = img.convert("RGBA")
        
        # 1. First trim any transparent or solid background (white)
        img = trim_bg(img)
        
        # 2. Make the background transparent if it was white
        # We can do this by converting white pixels to transparent
        # But a simple way is just to leave it as is if it's already cropped tightly
        # For favicon, tight cropping is the most important part so it fills the tab
        
        # Save the tightly cropped logo as logo.png
        # (Actually, let's keep logo.png as it was, or tightly cropped)
        img.save("public/images/logo.png", format="PNG")
        
        # Make a square version for the ICO
        max_dim = max(img.size)
        square_img = Image.new('RGBA', (max_dim, max_dim), (255, 255, 255, 0)) # transparent square
        offset = ((max_dim - img.width) // 2, (max_dim - img.height) // 2)
        square_img.paste(img, offset)
        
        # Save as favicon.ico
        square_img.save("public/favicon.ico", format="ICO", sizes=[(16, 16), (32, 32), (48, 48), (64, 64)])
        
        print("Success")
    except Exception as e:
        print("Error:", e)

if __name__ == "__main__":
    process_new_logo("public/favicon.ico.png")
