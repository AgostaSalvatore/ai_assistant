import axios from 'axios';
import { useState } from 'react';

export default function AiGenerator() {
    const [topic, setTopic] = useState('');
    const [tone, setTone] = useState('professionale')
    const [language, setLanguage] = useState('it')
    const [length, setLength] = useState('breve')
    const [loading, setLoading] = useState(false);

    const submit = async () => {
        if (loading) return;
        setLoading(true);

        try {
            const res = await axios.post('/ai-generator', { topic, tone, language, length });
            alert(JSON.stringify(res.data, null, 2));
        } catch (err) {
            alert(JSON.stringify(err.response?.data, null, 2));
        } finally {
            setLoading(false);
        }
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
            <select value={tone} onChange={(e) => setTone(e.target.value)}>
                <option value="professionale">Professionale</option>
                <option value="amichevole">Amichevole</option>
                <option value="ironico">Ironico</option>
            </select>

            <select value={language} onChange={(e) => setLanguage(e.target.value)}>
                <option value="it">Italiano</option>
                <option value="en">English</option>
            </select>

            <select value={length} onChange={(e) => setLength(e.target.value)}>
                <option value="breve">Breve</option>
                <option value="medio">Medio</option>
                <option value="lungo">Lungo</option>
            </select>

            <button onClick={submit} disabled={loading}>
                {loading ? 'Genero...' : 'Genera'}
            </button>
        </div>
    );
}