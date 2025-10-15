import { createRoot } from 'react-dom/client';
import AppLoader from './AppLoader';

const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(
        <React.StrictMode>
            <AppLoader minDisplayMs={500} />
            <div>Mon portfolio</div>
        </React.StrictMode>
    );
}
