# Laravel + Vue.js Invoice Application

This project involves creating an Invoice Management System with user authentication, dynamic invoice CRUD functionality, real-time calculations, PDF generation, and optional unit testing. Below are the key tasks organized as a To-Do List.

---

## 📋 To-Do List

### 1. User Authentication
- [✅] **Setup Authentication System**  
  Implement basic user authentication with username and password for secure access to the application.

### 2. CRUD for Invoices
#### Backend (Laravel)
- [✅] **Model and Migration for Invoices**  
  - Define fields:
    - `invoice_number`: Auto-generated, formatted as `INVYYMMDDXX`.
    - `invoice_date`: Date field.
    - `client_name`: String.
    - `client_address`: Text area.
    - `remarks`: Text area.
    - `discount_amount`: Decimal.
    - `subtotal`: Decimal.
    - `gst_amount`: Decimal (9% of subtotal).
    - `grand_total`: Decimal.
    
- [✅] **Model and Migration for Invoice Items**  
  - Define fields for items:
    - `invoice_id`: BigInteger (FK Invoice).
    - `item_name`: String.
    - `item_price`: Decimal.
    - `item_quantity`: Integer.
    - `item_amount`: Calculated as `item_price * item_quantity`.

#### Frontend (Vue.js)
- [🟡] **Create Invoice Form**  
  - Dynamic item fields for adding multiple items.
  - Real-time calculation of:
    - Item amount.
    - Invoice subtotal, GST, and grand total.
  - Auto-generation of the invoice number based on the format.

- [🟡] **CRUD Operations**  
  - Create, Read, Update, and Delete invoices with linked item entries.

### 3. Generate Invoice PDF
- [🟡] **PDF Generation**  
  - Implement a PDF export feature for invoices, ensuring a clean layout of invoice data.
  - Include the invoice number, client details, itemized list, subtotal, discount, GST, and grand total.

### 4. Unit Testing (Optional)
- [🟡] **Write Unit Tests**  
  - Cover essential functionalities:
    - User authentication.
    - CRUD operations for invoices.
    - Calculation logic for totals and GST.

---

### 🛠️ Installation & Setup

1. **Clone the repository**  
   ```bash
   git clone https://github.com/atqiyacode/the-pack-invoice-app.git
