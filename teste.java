public class Consulta extends AsyncTask<String, Void, Boolean> {
 
    @Override
    protected Boolean doInBackground(String... params) {
 
        String URL = "http://vandersonguidi.com.br/networkonmainthread.txt";
        String linha = "";
        Boolean Erro = true;
 
        if (params.length > 0)
            // faço qualquer coisa com os parâmetros
 
            try {
 
                HttpClient client = new DefaultHttpClient();
                HttpGet requisicao = new HttpGet();
                requisicao.setHeader("Content-Type",
                        "text/plain; charset=utf-8");
                requisicao.setURI(new URI(URL));
                HttpResponse resposta = client.execute(requisicao);
                BufferedReader br = new BufferedReader(new InputStreamReader(
                        resposta.getEntity().getContent()));
                StringBuffer sb = new StringBuffer("");
 
                while ((linha = br.readLine()) != null) {
                    sb.append(linha);
                }
 
                br.close();
 
                linha = sb.toString();
                Erro = false;
 
            } catch (Exception e) {
                Erro = true;
            }
 
        return Erro;
    }
}