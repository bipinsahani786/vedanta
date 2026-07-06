from PIL import Image
import sys

def process_favicon(input_path, output_path):
    try:
        img = Image.open(input_path)
        img = img.convert("RGBA")
        
        # Crop transparent edges to make the logo fill the entire icon space
        bbox = img.getbbox()
        if bbox:
            img = img.crop(bbox)
            
        # Create a solid dark blue image of the cropped size
        dark_blue = Image.new('RGB', img.size, color=(3, 27, 78))
        
        # Extract alpha from cropped original
        _, _, _, a = img.split()
        
        # Merge dark blue with original alpha
        final_img = Image.merge('RGBA', (*dark_blue.split(), a))
        
        # Ensure the image is square by pasting it into a transparent square
        # otherwise scaling to a square ICO might stretch it.
        max_dim = max(final_img.size)
        square_img = Image.new('RGBA', (max_dim, max_dim), (0,0,0,0))
        offset = ((max_dim - final_img.width) // 2, (max_dim - final_img.height) // 2)
        square_img.paste(final_img, offset)
        
        # Save as ICO
        square_img.save(output_path, format="ICO", sizes=[(16, 16), (32, 32), (48, 48), (64, 64)])
        print("Success")
    except Exception as e:
        print("Error:", e)

if __name__ == "__main__":
    process_favicon(sys.argv[1], sys.argv[2])
