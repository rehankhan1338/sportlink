<template>
    <section class="membership-plans" id="federation-plans">
      <div class="container">
        <div class="row">
          <form method="GET" action="#" accept-charset="UTF-8" class="plans-grid">
            <!-- Basic Plan -->
            <div class="plan basic">
              <div class="plan-card">
                <h2 class="plan-title">Basic</h2>
                <p class="plan-description">
                  Run your own circuit of events from your personal subdomain, choose if you require participating academies to be members with an optional fee for membership to your organisation. Grow your business by letting affiliate organisers run events under your umbrella.
                </p>
                <div class="plan-price">
                  <span class="price">20</span><span class="currency">€</span>
                  <span class="period">/ Month</span>
                </div>
                <ul class="features">
                  <li>
                    <h3>Academy membership</h3>
                    <p>Membership for academies lets you charge member academies a yearly fee (if you like) for being a part of your federation. You control who gets to participate at your events.</p>
                  </li>
                  <li>
                    <h3>Affiliate organisers</h3>
                    <p>Grow your business by letting affiliate organisers run events under your umbrella.</p>
                  </li>
                  <li>
                    <h3>Subdomain</h3>
                    <p>Choose yourname.smoothcomp.com which will be the landing page of your federation. You can always direct your personal web address to point at yourname.smoothcomp.com.</p>
                  </li>
                  <li>
                    <h3>Event listing</h3>
                    <p>On your subdomain only your federation events will be listed.</p>
                  </li>
                  <li>
                    <h3>Event templates</h3>
                    <p>Make a competition template for your federation to make creating events simple also for affiliate organisers.</p>
                  </li>
                </ul>
                <div class="plan-action">
                  <button
                    type="submit"
                    class="btn btn-primary"
                    name="plan"
                    value="Federation Basic"
                  >
                    Choose plan
                  </button>
                </div>
              </div>
            </div>
            <!-- Premium Plan -->
            <div class="plan premium">
              <div class="plan-card">
                <h2 class="plan-title">Premium</h2>
                <p class="plan-description">
                  Includes a CMS (add "About", "News", "Rules" or any page you like). Increased flexibility by offering individual membership to your federation as an extra layer and offer different levels of memberships for both academies and individuals. Create ranking lists for your divisions with our powerful ranking engine.
                </p>
                <div class="plan-price">
                  <span class="price">90</span><span class="currency">€</span>
                  <span class="period">/ Month</span>
                </div>
                <ul class="features">
                  <li>
                    <span class="label">Everything in Basic, plus:</span>
                  </li>
                  <li>
                    <h3>Individual membership</h3>
                    <p>This feature lets you add another layer of membership for individual users, you can have one fee for the academy and one for the individual if you so choose.</p>
                  </li>
                  <li>
                    <h3>Multiple membership plans</h3>
                    <p>If you have different levels of memberships, for instance, "support membership, normal membership and premium membership" you can now build those plans within your admin system.</p>
                  </li>
                  <li>
                    <h3>Ranking</h3>
                    <p>The ranking engine lets you set rules for different events, entries and classes so you can customize your own ranking list for your circuit.</p>
                  </li>
                  <li>
                    <h3>CMS - Content Management System</h3>
                    <p>Build your federation page as you want with multiple pages/menus or add a news page.</p>
                  </li>
                  <li>
                    <h3>Custom Metadata</h3>
                    <p>Custom metadata can be useful if you want to record information about previous merits, belts or other achievements. You can also have your members upload documents for you to check before approving their membership.</p>
                  </li>
                </ul>
                <div class="plan-action">
                  <button
                    type="submit"
                    class="btn btn-primary"
                    name="plan"
                    value="Federation Premium"
                  >
                    Choose plan
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
  </template>
  
  <script setup></script>
  
  <style scoped>
  /* Container styling */
  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 16px;
  }
  
  /* Responsive grid */
  .plans-grid {
    display: grid;
    gap: 16px;
    grid-template-columns: 1fr;
  }
  
  @media (min-width: 768px) {
    .plans-grid {
      grid-template-columns: 1fr 1fr;
    }
  }
  
  /* Plan card */
  .plan-card {
    background: #000000;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }
  
  .plan-title {
    font-size: 1.5rem;
    margin-bottom: 16px;
    color: #ffffff;
  }
  
  .plan-description {
    font-size: 1rem;
    margin-bottom: 16px;
    color: #ffffff;
  }
  
  .plan-price {
    font-size: 2rem;
    margin-bottom: 16px;
    color: #ffffff;
  }
  
  .plan-price .currency {
    font-size: 1.5rem;
  }
  
  .features {
    margin: 0;
    padding: 0;
    list-style: none;
    color: rgb(255, 254, 253);
    margin-bottom: 16px;
  }
  
  .features li {
    margin-bottom: 12px;
  }
  
  .plan-action .btn {
    width: 100%;
    padding: 12px 16px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-align: center;
  }
  
  .plan-action .btn:hover {
    background-color: #0056b3;
  }
  
  /* Mobile-first design with media query adjustments */
  @media (min-width: 768px) {
    .plan-card {
      padding: 32px;
    }
  
    .plan-title {
      font-size: 2rem;
    }
  
    .plan-description {
      font-size: 1.125rem;
    }
  
    .plan-price {
      font-size: 2.5rem;
    }
  }
  </style>
  