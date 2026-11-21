// Guest Post Agency Management System - Mock Database & Data Store

const initialData = {
  sites: [
    {
      id: "SITE-101",
      domain: "techcrunch.com",
      niche: "Tech & Software",
      da: 92,
      dr: 88,
      traffic: "1.8M",
      tat: "3-5 Days",
      vendorCost: 450,
      sellingPrice: 750,
      vendorName: "Alex Rivera (TechMedia)",
      status: "Active"
    },
    {
      id: "SITE-102",
      domain: "financebuzz.com",
      niche: "Finance & Crypto",
      da: 78,
      dr: 74,
      traffic: "450K",
      tat: "2-4 Days",
      vendorCost: 180,
      sellingPrice: 350,
      vendorName: "Sarah Jenkins (FinMedia)",
      status: "Active"
    },
    {
      id: "SITE-103",
      domain: "healthline.com",
      niche: "Health & Fitness",
      da: 89,
      dr: 86,
      traffic: "2.5M",
      tat: "5-7 Days",
      vendorCost: 600,
      sellingPrice: 950,
      vendorName: "Dr. Mark Sloan",
      status: "Active"
    },
    {
      id: "SITE-104",
      domain: "realestatenews.org",
      niche: "Real Estate",
      da: 62,
      dr: 59,
      traffic: "120K",
      tat: "1-3 Days",
      vendorCost: 80,
      sellingPrice: 180,
      vendorName: "Prime Vendor Agency",
      status: "Active"
    },
    {
      id: "SITE-105",
      domain: "fashionweekly.net",
      niche: "Fashion & Lifestyle",
      da: 55,
      dr: 52,
      traffic: "85K",
      tat: "2 Days",
      vendorCost: 60,
      sellingPrice: 140,
      vendorName: "Elena Rostova",
      status: "Active"
    },
    {
      id: "SITE-106",
      domain: "searchengineland.com",
      niche: "Marketing & SEO",
      da: 84,
      dr: 81,
      traffic: "680K",
      tat: "3-5 Days",
      vendorCost: 300,
      sellingPrice: 580,
      vendorName: "SEOWorld Vendors",
      status: "Active"
    }
  ],
  orders: [
    {
      id: "ORD-9081",
      clientName: "CyberPulse Agency",
      domain: "techcrunch.com",
      targetUrl: "https://cyberpulse.io/cloud-security",
      anchorText: "best cloud security tools",
      vendorCost: 450,
      sellingPrice: 750,
      liveUrl: "https://techcrunch.com/2026/08/future-of-cloud-security",
      indexingStatus: "Indexed",
      doFollow: true,
      status: "Live/Published",
      date: "2026-09-02"
    },
    {
      id: "ORD-9082",
      clientName: "Alpha Capital",
      domain: "financebuzz.com",
      targetUrl: "https://alphacapital.com/crypto-investing",
      anchorText: "crypto portfolio management",
      vendorCost: 180,
      sellingPrice: 350,
      liveUrl: "Pending Vendor Output",
      indexingStatus: "Pending",
      doFollow: true,
      status: "Sent to Vendor",
      date: "2026-09-04"
    },
    {
      id: "ORD-9083",
      clientName: "NutriLife Health",
      domain: "healthline.com",
      targetUrl: "https://nutrilife.org/keto-guide",
      anchorText: "keto diet advantages",
      vendorCost: 600,
      sellingPrice: 950,
      liveUrl: "https://healthline.com/nutrition/keto-benefits-2026",
      indexingStatus: "Indexed",
      doFollow: true,
      status: "Live/Published",
      date: "2026-09-05"
    },
    {
      id: "ORD-9084",
      clientName: "EstatesPro",
      domain: "realestatenews.org",
      targetUrl: "https://estatespro.co/miami-villas",
      anchorText: "luxury Miami homes",
      vendorCost: 80,
      sellingPrice: 180,
      liveUrl: "Under Review by Editor",
      indexingStatus: "Pending",
      doFollow: true,
      status: "Content Review",
      date: "2026-09-06"
    }
  ],
  vendors: [
    {
      id: "VEND-01",
      name: "Alex Rivera (TechMedia)",
      email: "alex@techmediaoutreach.io",
      paymentMethod: "PayPal / Crypto",
      totalSites: 14,
      avgTat: "3 Days",
      totalEarned: 6300
    },
    {
      id: "VEND-02",
      name: "Sarah Jenkins (FinMedia)",
      email: "sarah@finmediaoutreach.com",
      paymentMethod: "Bank Wire / Stripe",
      totalSites: 8,
      avgTat: "2 Days",
      totalEarned: 4200
    },
    {
      id: "VEND-03",
      name: "Prime Vendor Agency",
      email: "orders@primevendors.net",
      paymentMethod: "Payoneer",
      totalSites: 35,
      avgTat: "2 Days",
      totalEarned: 12500
    }
  ],
  clients: [
    {
      id: "CLI-01",
      name: "CyberPulse Agency",
      contactPerson: "Michael Scott",
      email: "m.scott@cyberpulse.io",
      ordersCount: 18,
      totalSpent: 14500
    },
    {
      id: "CLI-02",
      name: "Alpha Capital",
      contactPerson: "Jessica Pearson",
      email: "j.pearson@alphacapital.com",
      ordersCount: 7,
      totalSpent: 5200
    },
    {
      id: "CLI-03",
      name: "NutriLife Health",
      contactPerson: "David Miller",
      email: "david@nutrilife.org",
      ordersCount: 12,
      totalSpent: 11800
    }
  ]
};

if (!localStorage.getItem('guestPostAppData')) {
  localStorage.setItem('guestPostAppData', JSON.stringify(initialData));
}

function getAppData() {
  return JSON.parse(localStorage.getItem('guestPostAppData')) || initialData;
}

function saveAppData(data) {
  localStorage.setItem('guestPostAppData', JSON.stringify(data));
}
