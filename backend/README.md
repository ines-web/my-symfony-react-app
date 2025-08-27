# Product Catalog API

A simple RESTful API for managing a product catalog, built with Symfony 7.3 and PHP 8.2.

## Features

- List all products
- Search products by name or ID
- Add new products (protected by authentication)
- Dockerized environment for easy setup

## Requirements

- Docker and Docker Compose
- Git

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   cd my-symfony-react-app/backend
   ```

2. Start the Docker containers:
   ```
   docker-compose up -d
   ```

3. The API will be available at: http://localhost:8000

## API Endpoints

### List all products
```
GET /api/products
```

**Response:**
```json
{
  "products": [
    {
      "id": 1,
      "name": "Product Name",
      "category": "Category",
      "price": 19.99,
      "createdAt": "2025-08-27T15:02:00+00:00"
    }
  ]
}
```

### Search products
```
GET /api/products/search?q=search_term
```

- Search by name: `/api/products/search?q=product`
- Search by ID: `/api/products/search?q=1`

**Response:**
```json
{
  "products": [
    {
      "id": 1,
      "name": "Product Name",
      "category": "Category",
      "price": 19.99,
      "createdAt": "2025-08-27T15:02:00+00:00"
    }
  ]
}
```

### Add a product (Authentication required)
```
POST /api/products
```

**Headers:**
```
Authorization: Basic YWRtaW46YWRtaW4=  (admin:admin)
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "New Product",
  "category": "Electronics",
  "price": 29.99
}
```

**Response:**
```json
{
  "message": "Product created successfully",
  "product": {
    "id": 2,
    "name": "New Product",
    "category": "Electronics",
    "price": 29.99,
    "createdAt": "2025-08-27T15:10:00+00:00"
  }
}
```

## Authentication

The API uses HTTP Basic Authentication for the add product endpoint. The default credentials are:

- Username: `admin`
- Password: `admin`

## Implementation Details

- **Database**: PostgreSQL
- **ORM**: Doctrine
- **API Format**: JSON
- **Authentication**: HTTP Basic Auth with in-memory users

## Future Improvements

- Add pagination for product listing
- Implement JWT authentication
- Add more product fields (description, image, etc.)
- Add unit and functional tests
- Add product update and delete endpoints
- Implement caching for better performance

## Verification Instructions

To verify that the API endpoints are working correctly, you can use tools like cURL, Postman, or any HTTP client.

### 1. Start the application
```
docker-compose up -d
```

### 2. Test the list products endpoint
```
curl http://localhost:8000/api/products
```

### 3. Add a product (requires authentication)
```
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Basic YWRtaW46YWRtaW4=" \
  -d '{"name":"Test Product","category":"Test","price":9.99}'
```

### 4. Search for the added product
```
curl http://localhost:8000/api/products/search?q=Test
```

### 5. Try adding a product without authentication
```
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{"name":"Unauthorized Product","category":"Test","price":9.99}'
```
This should return a 401 Unauthorized response.