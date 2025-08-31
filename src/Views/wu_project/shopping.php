<?php $title = 'Shopping Page'; ?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        text-align: center;
    }
    .container {
        width: 80%;
        margin: auto;
    }
    .product {
        display: inline-block;
        width: 30%;
        margin: 10px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .product img {
        width: 100%;
        height: auto;
    }
    button {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background-color: #e6c808;
    }
</style>

<h1>G3 Hello</h1>
<div class="container">
    <div class="product">
        <img src="/wu_project/photo/photo_2024-10-17_12-46-50 (2).jpg" alt="Color white">
        <h2>Urben Aura</h2>
        <p>$17.99</p>
        <button>Can Pay</button>
    </div>
    <div class="product">
        <img src="/wu_project/photo/photo_2024-10-17_13-05-45.jpg" alt="Color Black">
        <h2>Urben Aura</h2>
        <p>$17.99</p>
        <button>Can Pay</button>
    </div>
</div>
