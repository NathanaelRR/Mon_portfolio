// import './bootstrap';
// import React from 'react';
import { createRoot } from 'react-dom/client';
import AppLoader from './AppLoader'; // 👈 ton composant loader
import App from './App'; // 👈 ton composant principal

const container = document.getElementById('app');

if (container) {
  const root = createRoot(container);

//   root.render(
//     <React.StrictMode>
//       <AppLoader minDisplayMs={500} />
//       <App />
//     </React.StrictMode>
//   );

root.render(App());


  // Quand ton app est prête, cache le loader
  window.finishAppLoader();
}
