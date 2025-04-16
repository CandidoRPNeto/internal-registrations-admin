class ApiAdapter {
    constructor(baseUrl = 'http://localhost:8000/api') {
        this.baseUrl = baseUrl;
    }

    async request(endpoint, method = 'GET', data = null, need_token = true) {
        const url = `${this.baseUrl}/${endpoint}`; 
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };

        if (need_token) {
            const token = localStorage.getItem('token');
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
        }
        const options = {
            method,
            headers,
            credentials: 'include'
        };

        if (data && (method === 'POST' || method === 'PUT')) {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, options);
            if (response.status >= 400) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Erro na requisição');
            }

            return await response.json();
        } catch (error) {
            console.error('Erro na requisição:', error);
            throw error;
        }
    }

    async login(email, password) {
        return this.request('auth/login', 'POST', { email, password }, false);
    }

    async logout() {
        return this.request('auth/logout', 'POST');
    }

    async getDashInfo() {
        return this.request(`dashboard/quantity`);
    }

    async listarTurmas(pagina = 1) {
        return this.request(`classroom?page=${pagina}`);
    }

    async cadastrarTurma(dadosTurma) {
        return this.request('classroom', 'POST', dadosTurma);
    }

    async obterTurma(id) {
        return this.request(`classroom/${id}`);
    }

    async atualizarTurma(id, dadosTurma) {
        return this.request(`classroom/${id}`, 'PUT', dadosTurma);
    }

    async removerTurma(id) {
        return this.request(`classroom/${id}`, 'DELETE');
    }

    
    async matricularAluno(idAluno, idTurma) {
        return this.request('enrollment', 'POST', {
            aluno_id: idAluno,
            turma_id: idTurma
        });
    }
    
    async listarMatriculas(params) {
        let url = `enrollment?page=${params.page}`;
    
        if (params.search && params.filter) {
            url += `&search=${encodeURIComponent(params.search)}&filter=${encodeURIComponent(params.filter)}`;
        }
        
        return this.request(url);
    }
    
    async cancelarMatricula(idMatricula) {
        return this.request(`enrollment/${idMatricula}`, 'DELETE');
    }


    async listarAlunos(pagina = 1, termoBusca = '') {
        const endpoint = `student?page=${pagina}&search=${termoBusca}`;
        return this.request(endpoint);
    }

    async obterAluno(id) {
        return this.request(`student/${id}`);
    }

    async cadastrarAluno(dadosAluno) {
        return this.request('student', 'POST', dadosAluno);
    }

    async atualizarAluno(id, dadosAluno) {
        return this.request(`student/${id}`, 'PUT', dadosAluno);
    }

    async removerAluno(id) {
        return this.request(`student/${id}`, 'DELETE');
    }


}

const api = new ApiAdapter();