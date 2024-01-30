<?php
#EASY DATABASE SETUP
require __DIR__ . '/databse/connection.php';

date_default_timezone_set('Europe/Lisbon');

$tablesToCheck = ['USERS', 'PAYMENTMETHODS', 'EXPENSECATEGORIES', 'EXPENSES', 'SHAREDEXPENSES'];
$doTablesExist = true;

foreach ($tablesToCheck as $table) {
    $tableExistQuery = "SHOW TABLES LIKE '$table'";
    $doTableExistStatement = $pdo->query($tableExistQuery);

    if ($doTableExistStatement -> rowCount() == 0) {
        $doTablesExist = false;
        break;
    }
}

if(!$doTablesExist) {
    $tablesToDrop = ['SHAREDEXPENSES', 'EXPENSES', 'EXPENSE', 'PAYMENTMETHODS', 'USERS'];

    foreach ($tablesToDrop as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table;");
    }

    foreach ($tablesToDrop as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table;");
    }

    $pdo->exec('
        CREATE TABLE USERS (
            userID int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            firstName varchar(255) NOT NULL,
            lastName varchar(255) NOT NULL,
            password varchar(255) NOT NULL,
            emailAddress varchar(255) NOT NULL,
            avatar longblob NULL,
            country varchar(255) NULL,
            dateOfBirth date NULL,
            isAdmin BOOLEAN NOT NULL DEFAULT false,
            createdAt timestamp NULL DEFAULT NULL,
            updatedAt timestamp NULL DEFAULT NULL,
            deletedAt timestamp NULL DEFAULT NULL,
            PRIMARY KEY (userID),
            UNIQUE KEY users_id_unique (id)
        )

        CREATE TABLE PAYMENTMETHODS (
            paymentMethodID int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            paymentMethod varchar(255) NOT NULL,
            PRIMARY KEY (paymentMethodID)
        )

        CREATE TABLE EXPENSECATEGORIES (
            expenseCategoryID int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            expenseCategory varchar(255) NOT NULL,
            PRIMARY KEY (expenseCategoryID)
        )

        CREATE TABLE EXPENSES (
            expenseID int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            expenseCategoryID int(20) UNSIGNED NOT NULL,
            expenseDescription varchar(255) NOT NULL,
            paymentMethodID int(20) UNSIGNED NOT NULL,
            paidAmount decimal(10,2) NOT NULL,
            paymentDate date NOT NULL,
            fullyPaid BOOLEAN NOT NULL DEFAULT false,
            notes varchar(255) DEFAULT NULL,
            userID int(20) UNSIGNED NOT NULL,
            createdAt timestamp NULL DEFAULT NULL,
            updatedAt timestamp NULL DEFAULT NULL,
            deletedAt timestamp NULL DEFAULT NULL,
            PRIMARY KEY (expenseID),
            KEY expenses_category_id_foreign_key (expenseCategoryID),
            KEY expenses_payment_id_foreign_key (paymentMethodID),
            KEY expenses_user_id_foreign_key (userID),
            CONSTRAINT expensesCategoryIDForeignKey FOREIGN KEY (expenseCategoryID) REFERENCES categories (expenseCategoryID),
            CONSTRAINT expensesPaymentMethodIDForeignKey FOREIGN KEY (paymentMethodID) REFERENCES methods (paymentMethodID),
            CONSTRAINT expensesUserIDForeignKey FOREIGN KEY (userID) REFERENCES users (userID)
        )
        
        CREATE TABLE SHAREDEXPENSES (
            sharedExpenseID int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            sentToUserID int(20) UNSIGNED NOT NULL,
            fromUserID int(20) UNSIGNED NOT NULL,
            expenseID int(20) UNSIGNED NOT NULL,
            createdAt timestamp NULL DEFAULT NULL,
            updatedAt timestamp NULL DEFAULT NULL,
            deletedAt timestamp NULL DEFAULT NULL,
            PRIMARY KEY (sharedExpenseID),
            KEY sharedExpensesSentToUserIDForeignKey (sentToUserID),
            KEY sharedExpensesFromUserIDForeignKey (fromUserID),
            KEY sharedExpensesExpenseIDForeignKey (expenseID),
            CONSTRAINT sharedExpensesSentToUserIDForeignKey FOREIGN KEY (sentToUserID) REFERENCES users (id),
            CONSTRAINT sharedExpensesFromUserIDForeignKey FOREIGN KEY (fromUserID) REFERENCES users (id),
            CONSTRAINT sharedExpensesExpenseIDForeignKey FOREIGN KEY (expenseID) REFERENCES expenses (expenseID),
            CONSTRAINT usersUnicity CHECK (sentToUserID <> fromUserID)
        )
    ');
        
    $insertUser= [
        [
            'firstName' => 'Pinto',
            'lastName' => 'da Costa',
            'emailAddress' => 'admin@admin.admin',
            'country' => 'Portugal',
            'dateOfBirth' => '1937/12/28',
            'password' => 'SUPER123',
            'avatar' => null,
            'isAdmin' => true,
            'createdAt' => date('Y-m-d H:i:s'),
            'updatedAt' => date('Y-m-d H:i:s')
        ]
    ];

    foreach ($insertUser as $user) {
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

        $sqlCreateUser = "INSERT INTO 
            users (
                firstName,
                lastName, 
                password,
                avatar,
                emailAddress,
                country,
                dateOfBirth,
                isAdmin,
                createdAt,
                updatedAt
            ) 
            VALUES (
                :firstName,
                :lastName,  
                :password,
                :avatar,
                :emailAddress,
                :country,
                :dateOfBirth,
                :isAdmin,
                :createdAt,
                :updatedAt
            )";

        $PDOStatementUser = $pdo->prepare($sqlCreateUser);

        $successUser = $PDOStatementUser->execute([
            ':firstName' => $user['firstName'],
            ':lastName' => $user['lastName'],
            ':password' => $user['password'],
            ':avatar' => $user['avatar'],
            ':emailAddress' => $user['emailAddress'],
            ':country' => $user['country'],
            ':dateOfBirth' => $user['dateOfBirth'],
            ':isAdmin' => $user['isAdmin'],
            ':createdAt' => $user['createdAt'],
            ':updatedAt' => $user['updatedAt']
        ]);

        if (!$successUser) {
            echo "Error adding user: ".implode(" - ", $PDOStatementUser -> errorInfo()).PHP_EOL;
        }
    }

    $categoriesToInsert = [
        ['description' => 'General'],        
        ['description' => 'Food'],
        ['description' => 'Transportation'],
        ['description' => 'Utilities'],
        ['description' => 'Entertainment'],
        ['description' => 'Rent'],
        ['description' => 'Insurance'],        
        ['description' => 'Mechanic'],        
        ['description' => 'Payroll Taxes'],        
        ['description' => 'Healthcare'],        
        ['description' => 'Investing'],        
        ['description' => 'Debt Payments'],        
        ['description' => 'Personal'],        
        ['description' => 'Miscellaneous'],        
        ['description' => 'Communication'],      
        ['description' => 'Housing'], 
        ['description' => 'Other'],     
    ];

    foreach ($categoriesToInsert as $category) {
        $CreateQuery = "INSERT INTO categories (expenseCategory, createdAt, updatedAt) 
                                VALUES (:expenseCategory, NOW(), NOW())";
        
        $Statement = $pdo -> prepare($CreateQuery);

        $Success = $Statement -> execute([
            ':expenseCategory' => $category['expenseCategory']
        ]);

        if (!$Success) {
            echo "Error adding a category: ".implode(" - ", $Statment -> errorInfo()).PHP_EOL;
        }
    }
}