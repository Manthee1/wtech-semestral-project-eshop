## User Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the user |
| username    | varchar  | User's username |
| password    | varchar  | User's password |
| email       | varchar  | User's email address |
| phone       | varchar  | User's phone number |
| street_address | varchar | User's street address |
| city        | varchar  | User's city |
| first_name  | varchar  | User's first name |
| last_name   | varchar  | User's last name |
| name_on_card | varchar | User's name on card |
| card_number | varchar  | User's card number |
| expiration_date | varchar | User's card expiration date |
| cvv         | varchar  | User's card cvv |
| role        | varchar  | User's role |
| created_at  | datetime | Date and time when the user was created |

## Products Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the product |
| name        | varchar  | Name of the product |
| price       | decimal  | Price of the product |
| description | text     | Description of the product |
| stock       | int      | Stock of the product |
| make_id        | int  | Make of the product |
| drivetrain_id  | int  | Drivetrain of the product |
| body_type   | int  | Body type of the product |
| gas_mileage | int  | Gas mileage of the product |
| engine_type_id | int  | Engine type of the product |
| height      | int  | Height of the product |
| width       | int  | Width of the product |
| length      | int  | Length of the product |
| model_id       | int  | Model of the product |
| horse_power | int  | Horse power of the product |
| passanger_capacity | varchar  | Passanger capacity of the product |
| year        | int  | Year of the product |
| created_at  | datetime | Date and time when the product was created |

## Product Makes Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the make |
| name        | varchar  | Name of the make |
| created_at  | datetime | Date and time when the make was created |

## Product Drivetrains Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the drivetrain |
| name        | varchar  | Name of the drivetrain |
| created_at  | datetime | Date and time when the drivetrain was created |

## Product Body Types Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the body type |
| name        | varchar  | Name of the body type |
| created_at  | datetime | Date and time when the body type was created |

## Product Engine Types Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the engine type |
| name        | varchar  | Name of the engine type |
| created_at  | datetime | Date and time when the engine type was created |

## Product Models Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the model |
| name        | varchar  | Name of the model |
| created_at  | datetime | Date and time when the model was created |

## Product Images Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the image |
| product_id  | int      | Foreign key referencing the product |
| url         | varchar  | URL of the image |
| created_at  | datetime | Date and time when the image was created |

## Orders Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the order |
| user_id     | int      | Foreign key referencing the user |
| total       | decimal  | Total amount of the order |
| created_at  | datetime | Date and time when the order was created |

## Cart Table

| Column Name | Data Type | Description |
|-------------|----------|-------------|
| id          | int      | Unique identifier for the cart |
| user_id     | int      | Foreign key referencing the user |
| product_id  | int      | Foreign key referencing the product |
| quantity    | int      | Quantity of the product in the cart |
| created_at  | datetime | Date and time when the cart was created |

---

```sql
Table Users {
  id int [pk]
  username varchar
  password varchar
  email varchar
  phone varchar
  street_address varchar
  city varchar
  first_name varchar
  last_name varchar
  name_on_card varchar
  card_number varchar
  expiration_date varchar
  cvv varchar
  role varchar
  created_at datetime
}

Table Products {
  id int [pk]
  name varchar
  price decimal
  description text
  stock int
  make_id int [ref: > ProductMakes.id]
  drivetrain_id int [ref: > ProductDrivetrains.id]
  body_type int [ref: > ProductBodyTypes.id]
  gas_mileage int
  engine_type_id int [ref: > ProductEngineTypes.id]
  height int
  width int
  length int
  model_id int [ref: > ProductModels.id]
  horse_power int
  passanger_capacity varchar
  year int
  created_at datetime
}

Table ProductMakes {
  id int [pk]
  name varchar
  created_at datetime
}

Table ProductDrivetrains {
  id int [pk]
  name varchar
  created_at datetime
}

Table ProductBodyTypes {
  id int [pk]
  name varchar
  created_at datetime
}

Table ProductEngineTypes {
  id int [pk]
  name varchar
  created_at datetime
}

Table ProductModels {
  id int [pk]
  name varchar
  created_at datetime
}

Table ProductImages {
  id int [pk]
  product_id int [ref: > Products.id]
  url varchar
  created_at datetime
}

Table Orders {
  id int [pk]
  user_id int [ref: > Users.id]
  total decimal
  created_at datetime
}

Table Cart {
  id int [pk]
  user_id int [ref: > Users.id]
  product_id int [ref: > Products.id]
  quantity int
  created_at datetime
}

```