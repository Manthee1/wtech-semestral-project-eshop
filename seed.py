import os
import random
import shutil
import numpy as np
from essential_generators import DocumentGenerator
gen = DocumentGenerator()

# Define the path to the dataset folder
dataset_folder = './.idea/thecarconnectionpicturedataset'

# Define the path to the imported folder
imported_folder = './public/img/products'

# Define output file
output_file = './products-seed.sql'

# Define the number of images to import
num_images_to_import = 100

# Define the list of parameters to save

# Define the list of tables
makes_table = {}
models_table = {}
drivetrains_table = {}
body_types_table = {}
engine_types_table = {}
images_table = []
products_table = {}

def gen_description():
    # Generate 2-5 sentences with gen.sentence()
    description=''
    for _ in range(random.randint(2, 5)):
        description += gen.sentence() + ' '
    # Replace every ' with ''
    description = description.replace("'", "''")

    return description



# Function to import images
def import_images():
    # Get a list of all image files in the dataset folder
    image_files = [f for f in os.listdir(dataset_folder) if os.path.isfile(os.path.join(dataset_folder, f))]

    # Randomly select N number of images to import
    selected_images = random.sample(image_files, num_images_to_import)

    # Import all images
    # selected_images = image_files

    # Iterate over the selected images
    for image in selected_images:

        # Image parameters ar saved in the image file name according to the following format separated by underscores
        # 'Make', 'Model', 'Year', 'MSRP', 'Front Wheel Size (in)', 'SAE Net Horsepower @ RPM', 'Displacement', 'Engine Type', 'Width, Max w/o mirrors (in)', 'Height, Overall (in)', 'Length, Overall (in)', 'Gas Mileage', 'Drivetrain', 'Passenger Capacity', 'Passenger Doors', 'Body Style'
        # We want ['Make', 'Model', 'Year', 'Body Type/Style', 'Horse Power', 'Engine Type', 'Height', 'Length', 'Width', 'Gas Mileage', 'Drivetrain', 'Passenger Capacity']


        # Get the car's parameters from the image file name
        parameter_list = image.split('_')

         # Change all the nan values to None
        for i in range(len(parameter_list)):
            if parameter_list[i] == 'nan':
                parameter_list[i] = 'NULL'
        parameters = {
            'make': str(parameter_list[0]),
            'model': str(parameter_list[1]),
            'year': parameter_list[2],
            'body_type': str(parameter_list[15]),
            'horse_power': parameter_list[5] if parameter_list[5].isdigit() else str(random.randint(50, 200)),
            'engine_type': str(parameter_list[7]),
            'height': parameter_list[9] if parameter_list[9].isdigit() else str(random.randint(50, 200)),
            'width': parameter_list[8] if parameter_list[8].isdigit() else str(random.randint(50, 200)),
            'length': parameter_list[10] if parameter_list[10].isdigit() else str(random.randint(50, 200)),
            'efficiency': parameter_list[11],
            'drivetrain': str(parameter_list[12]),
            'passenger_capacity': parameter_list[13] if parameter_list[13].isdigit() else str(random.randint(2, 10))
        }
        # Convert efficiency from gallons per mile to liters per kilometer
        if(parameters['efficiency'] != 'NULL'):
            efficiency_mpg = int(parameters['efficiency'])
            efficiency_lpk = 235.214583 / efficiency_mpg
            parameters['efficiency'] = round(efficiency_lpk, 2)


        car_name = parameters['make'] + '_' + parameters['model'] + '_' + parameters['year']
        parameters['name'] = parameters['make'] + ' ' + parameters['model'] + ' ' + parameters['year']
        # Generate a unique name for the imported image
        imported_image_name = f"{car_name}_{random.randint(1, 1000)}.jpg"

        # Copy the image to the imported folder and rename it
        shutil.copy2(os.path.join(dataset_folder, image), os.path.join(imported_folder, imported_image_name))

        makes_table[parameters['make']] = {'name': parameters['make']}
        models_table[parameters['model']] = {'name': parameters['model'], 'make': parameters['make']}
        drivetrains_table[parameters['drivetrain']] = {'name': parameters['drivetrain']}
        body_types_table[parameters['body_type']] = {'name': parameters['body_type']}
        engine_types_table[parameters['engine_type']] = {'name': parameters['engine_type']}
        images_table.append({'filename': imported_image_name, 'product_id': parameters['name']})
        products_table[car_name] = {'name':parameters['name'], 'price': random.randint(500, 50000), 'description': gen_description(), 'stock': random.randint(0, 100), 'make': parameters['make'], 'model': str(parameters['model']), 'year': parameters['year'], 'body_type': str(parameters['body_type']), 'horse_power': parameters['horse_power'], 'engine_type': str(parameters['engine_type']), 'height': parameters['height'], 'width': parameters['width'], 'length': parameters['length'], 'efficiency': parameters['efficiency'], 'drivetrain': str(parameters['drivetrain']), 'passenger_capacity': parameters['passenger_capacity']}


# Create the imported folder if it doesn't exist
if not os.path.exists(imported_folder):
    os.makedirs(imported_folder)
# Remove the images from the imported folder
for file in os.listdir(imported_folder):
    os.remove(os.path.join(imported_folder, file))



# Call the function to import images
import_images()


def print_table(table):
    # Print the table header
    table =np.array(list(table))
    print('---------------------------------')
    for key in table[0]:
        print(key, end='\t|\t')
    print('\n---------------------------------')
    # Print the table data
    for values in table:
        # Convert the dictionary values to a list
        values = list(values.values())
        for value in values:
            print(value, end='\t|\t')
        print('')


# make SQL queries
# Makes Table:
with open(output_file, 'w') as sql_file:
    for make in makes_table.values():
        sql_file.write(f"INSERT INTO product_makes (name) VALUES ('{make['name']}');\n")

    for model in models_table.values():
        sql_file.write(f"INSERT INTO product_models (name, make_id) VALUES ('{model['name']}', (SELECT id FROM product_makes WHERE name = '{model['make']}'));\n")

    for drivetrain in drivetrains_table.values():
        if drivetrain['name'] == 'NULL':
            continue
        sql_file.write(f"INSERT INTO product_drivetrains (name) VALUES ('{drivetrain['name']}');\n")

    for body_type in body_types_table.values():
        if body_type['name'] == 'NULL':
            continue
        sql_file.write(f"INSERT INTO product_body_types (name) VALUES ('{body_type['name']}');\n")

    for engine_type in engine_types_table.values():
        if engine_type['name'] == 'NULL':
            continue
        sql_file.write(f"INSERT INTO product_engine_types (name) VALUES ('{engine_type['name']}');\n")

    for product in products_table.values():
        if product['drivetrain'] == 'NULL':
            drivetrain_id = 'NULL'
        else:
            drivetrain_id = f"(SELECT id FROM product_drivetrains WHERE name = '{product['drivetrain']}')"
        if product['body_type'] == 'NULL':
            body_type_id = 'NULL'
        else:
            body_type_id = f"(SELECT id FROM product_body_types WHERE name = '{product['body_type']}')"
        if product['engine_type'] == 'NULL':
            engine_type_id = 'NULL'
        else:
            engine_type_id = f"(SELECT id FROM product_engine_types WHERE name = '{product['engine_type']}')"


        sql_file.write(f"INSERT INTO products (name, price, description, stock, make_id, drivetrain_id, body_type_id, efficiency, engine_type_id, height, width, length, model_id, horse_power, passenger_capacity, year) VALUES ('{product['name']}', {product['price']}, '{product['description']}', {product['stock']}, (SELECT id FROM product_makes WHERE name = '{product['make']}'), {drivetrain_id}, {body_type_id}, {product['efficiency']}, {engine_type_id}, {product['height']}, {product['width']}, {product['length']}, (SELECT id FROM product_models WHERE name = '{product['model']}' AND make_id = (SELECT id FROM product_makes WHERE name = '{product['make']}')), {product['horse_power']}, '{product['passenger_capacity']}', {product['year']});\n")

    for image in images_table:
        sql_file.write(f"INSERT INTO product_images (filename, product_id) VALUES ('{image['filename']}', (SELECT id FROM products WHERE name = '{image['product_id']}'));\n")
