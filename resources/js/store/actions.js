let actions = {

    //Create Agent Action
    createAgent({commit}, agent)
    {
        console.log(agent)
        axios.post('/api/v1/agent', agent)
            .then(res => {
                commit('CREATE_AGENT', res.data)
            }).catch(err => {
                console.log(err)
        })
    },

    // Fetch Agent Action
    fetchAgents({commit})
    {
        axios.get('/api/v1/agent')
            .then(res => {
                commit('FETCH_AGENTS', res.data)
            }).catch(err => {
                console.log(err)
        })
    },

    deleteAgent({commit}, agent)
    {
        axios.delete('/api/v1/agent/${agent.id}')
            .then(res => {
                if (res.data === 'ok')
                    commit('DELETE_AGENT', agent)
            }).catch(err => {
                console.log(err)
        })
    }
}

export default actions;
