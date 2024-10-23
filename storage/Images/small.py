import os
from PIL import Image
#this python file made to optmize images in folder
def resize_image(file_path, max_size=(1024, 1024), quality=85):
    try:
        with Image.open(file_path) as img:
            # Get the original image size
            orig_width, orig_height = img.size
            
            # Calculate the aspect ratio
            aspect_ratio = orig_width / orig_height
            
            # Determine new dimensions while maintaining aspect ratio
            if orig_width > orig_height:
                new_width = min(orig_width, max_size[0])
                new_height = int(new_width / aspect_ratio)
            else:
                new_height = min(orig_height, max_size[1])
                new_width = int(new_height * aspect_ratio)
            
            # Resize the image
            resized_img = img.resize((new_width, new_height), Image.LANCZOS)
            
            # Convert to RGB if the image is in RGBA mode
            if resized_img.mode == 'RGBA':
                resized_img = resized_img.convert('RGB')
            
            # Save the resized image with the same name
            resized_img.save(file_path, optimize=True, quality=quality)
        
        print(f"Resized and optimized: {file_path}")
    except Exception as e:
        print(f"Error processing {file_path}: {str(e)}")

def optimize_images_in_folder(folder_path, max_size=(1024, 1024)):
    for filename in os.listdir(folder_path):
        if filename.lower().endswith(('.png', '.jpg', '.jpeg', '.gif')):
            file_path = os.path.join(folder_path, filename)
            resize_image(file_path, max_size)

if __name__ == "__main__":
    folder_path = "."  # Current directory
    max_size = (1024, 1024)  # Maximum width and height for web use
    optimize_images_in_folder(folder_path, max_size)
    print("Image resizing and optimization complete.")