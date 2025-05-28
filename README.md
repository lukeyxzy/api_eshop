# 1. E-commerce PHP & JS Aplikace

> Webová aplikace sloužící jako e-shopové řešení. 

## 2. Cíl projektu

Cílem této práce je navrhnout a implementovat plně funkční backend i frontend eshopového systému, který:

* umožňuje přidávání a odstranění produktů v uživatelském košíku
* zajišťuje odeslání zákaznické objednávky do datábaze a následné zobrazení v uživatelském rozhraní
* využívá JWT autentizaci a role-based autorizaci
* podporuje validaci dat a logování serverových chyb
* poskytuje REST API přístup

## 3. Architektura systému

Třívrstvá architektura - 
Frontend: Javascript, HTML, CSS
Backend: PHP
Databáze: MariaDB

### Backend (PHP)

* **Router** – `UserRouter`, `ProductRouter`, `OrderRouter`, `OrderItemRouter`, `DeliveryAddressRouter`, `CategoryRouter`  
* **Model** – `User`, `Product`, `Order`, `OrderItem`, `DeliveryAddress`, `Category`  
* **Controller** – `UserController`, `ProductController`, `OrderController`, `CategoryController` 
* **Service** – `UserService`, `ProductService`, `OrderService`, `CategoryService` 
* **Repository** – `UserRepository`, `ProductRepository`, `OrderRepository`, `CategoryRepository` 
* **Utils** – `JwtHelper`

### Frontend (JS)

* **Pages** –  `index.js`,  `cart.js`, `orders.js`,  `orderDetails.js`,  `category.js`,  `product.js` 
* **Components** – `cart`, `category`, `header`, `order`, `user`
* **Autentizace** – uložení JWT pomocí `localStorage`

## 4. Bezpečnostní mechanismy

* **JWT autentizace** – uživatel získá token po přihlášení a posílá jej v hlavičce `Authorization: Bearer <token>`
* **Role-based access control** – `1` - ADMIN, `USER` - 0, `GUEST` - null
* **Ochrana endpointů** – jen oprávnění uživatelé mohou využít určité API

## 5. Validace

Při chybném vstupu je vrácen srozumitelný výstup s HTTP stavovým kódem:

```json
HTTP/1.1 400 Bad Request
Content-Type: application/json
{
  "error": "Nějsou vyplněna všechna povinná pole."
}
```

## 6. Ukázky API response

### 6.1 Úspěšný login

```http
POST /backend/userRouter.php/sign_in
```

```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR..."
}
```

### 6.2 Aktualizace alergenů

```http
GET /backend/userRouter.php/getLoggedInUserData
Authorization: Bearer <token>
```

```json
{
  "email" : "test@test.cz",
  "id" :30,
  "name": "Alex",
  "surname" : "Reed",
  "password" : "$2y$10$kz/hqZ1ED08iwHzJLMLcV.p706WzxFPpYuBumY26PioocFvUN9uYu",
   "role" : 0
}
```


## 8. Logování a monitoring

* Logování chyb aplikace do `error.log` souboru v /backend/ pomocí `error_log()` funkce:

```log
SQLSTATE[HY000] [2002] Nemohlo být vytvořeno žádné připojení, protože cílový počítač je aktivně odmítl LINE: 26 FILE: C:\xampp\htdocs\<App_name>\backend\src\model\Database.php
```

## 9. Spuštění projektu

Je potřeba vytvořit .env soubor v rootu projektu s následujícími data:

```env
DB_HOST=< Databázový host MariaDB\Mysql >
DB_NAME=< Jméno tabulky >
DB_USER=< Uživatelské jméno >
DB_PASSWORD=< Uživatelské heslo >
JWT_PASSWORD=< Náhodné heslo pro vygenerování JW tokenu >
```