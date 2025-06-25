// Base de datos: fitmatch_matches
db = db.getSiblingDB('fitmatch_matches');
db.createCollection("matches");
db.matches.createIndex({ user1_id: 1, user2_id: 1 }, { unique: true });

// Base de datos: fitmatch_activities
db = db.getSiblingDB('fitmatch_activities');
db.createCollection("activities");
db.activities.insertMany([
  {
    name: "Ir en bicicleta",
    description: "Ruta en bicicleta por el parque",
    category: "Aire libre",
    created_at: new Date()
  },
  {
    name: "Salida de gymbros",
    description: "Entrenamiento grupal en el gimnasio",
    category: "Gimnasio",
    created_at: new Date()
  }
  // Puedes agregar más actividades aquí
]); 