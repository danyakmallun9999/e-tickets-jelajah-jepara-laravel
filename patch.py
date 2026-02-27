import re

culinary_file = r"c:\laragon\www\e-tickets-jelajah-jepara-laravel\resources\views\public\culinary\show.blade.php"
culture_file = r"c:\laragon\www\e-tickets-jelajah-jepara-laravel\resources\views\public\culture\show.blade.php"

with open(culinary_file, "r", encoding="utf-8") as f:
    culinary_content = f.read()

# Extract the map section using regex
match = re.search(r'(<!-- Dedicated Map & Locations Section -->.*)@endif\n</x-public-layout>', culinary_content, re.DOTALL)
if match:
    map_code = match.group(1) + "@endif\n"
    
    # Replace $culinary with $culture
    map_code = map_code.replace('$culinary', '$culture')
    
    # Replace icons (storefront -> location_city)
    map_code = map_code.replace('storefront', 'location_city')
    
    # Replace Marker Icon from restaurant to location_on
    map_code = map_code.replace('<span class="material-symbols-outlined" style="font-size:18px;">restaurant</span>', '<span class="material-symbols-outlined" style="font-size:18px;">location_on</span>')
    
    # Replace text "Daftar Cabang" to "Daftar Lokasi"
    map_code = map_code.replace('Daftar Cabang', 'Daftar Lokasi')
    
    # Translations modification
    # __('Culinary.Detail.WantToTry') ?? 'Lokasi Terkait'
    map_code = map_code.replace("__('Culinary.Detail.WantToTry') ??", "")
    map_code = map_code.replace("__('Culinary.Detail.FindNearby', ['name' => $culture->name]) ??", "")
    map_code = map_code.replace('{{ \'Lokasi Terkait\' }}', '{{ \'Lokasi Terkait\' }}')
    
    # Let's just do a simpler replacement for those translations:
    map_code = re.sub(r"\{\{ __('Culinary\.Detail\.WantToTry') \?\? 'Lokasi Terkait' \}\}", "Lokasi Terkait", map_code)
    map_code = re.sub(r"\{\{ __('Culinary\.Detail\.FindNearby', \['name' => \$culture->name\]) \?\? \"([^\"]+)\" \}\}", r"\1", map_code)
    
    # Find that specific "Temukan cabang atau lokasi..."
    map_code = map_code.replace("Temukan cabang atau lokasi terkait dari $culture->name di bawah ini:", "Temukan lokasi terkait dari {{ $culture->name }} di bawah ini:")
    
    with open(culture_file, "r", encoding="utf-8") as f:
        culture_content = f.read()
        
    culture_content = culture_content.replace('</x-public-layout>', map_code + '\n</x-public-layout>')
    
    with open(culture_file, "w", encoding="utf-8") as f:
        f.write(culture_content)
        
    print("Map section transplanted successfully!")
else:
    print("Failed to extract map section.")
