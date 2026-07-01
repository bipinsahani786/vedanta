
from PIL import Image

def remove_bg(input_path, output_path, tolerance=100):
    img = Image.open(input_path).convert("RGBA")
    data = img.getdata()
    
    bg_color = data[0]
    new_data = []
    
    for item in data:
        dist = ((item[0] - bg_color[0])**2 + (item[1] - bg_color[1])**2 + (item[2] - bg_color[2])**2)**0.5
        if dist < tolerance:
            # For anti-aliasing edge, we could map alpha, but lets just make transparent
            new_data.append((255, 255, 255, 0))
        else:
            new_data.append(item)
            
    img.putdata(new_data)
    img.save(output_path, "PNG")

remove_bg("e:/Vedantaa/vedanta/public/images/logo.png", "e:/Vedantaa/vedanta/public/images/logo_transparent.png")
print("Done")

