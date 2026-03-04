import axios from 'axios';
import { useState } from 'react';

export default function AiGenerator() {
    const [topic, setTopic] = useState('');

    const submit = async () => {
        const res = await axios.post('/ai-generator', {
            topic,
            tone: 'professionale',
            language: 'it',
        });

        alert(JSON.stringify(res.data, null, 2));
    };

    return (
        <div>
            <h1>AI Content Generator</h1>

            <input
                type="text"
                placeholder="Inserisci l'argomento..."
                value={topic}
                onChange={(e) => setTopic(e.target.value)}
            />

            <button onClick={submit}>Genera</button>
        </div>
    );
}