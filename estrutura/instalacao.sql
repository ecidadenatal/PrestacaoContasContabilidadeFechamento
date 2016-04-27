
create table plugins.prestacaocontascontabilidadefechamento (sequencial integer, 
                                                    		 reduzido integer not null, 
                                                    		 mes integer not null,
                                                    		 exercicio integer not null,
															 status integer not null,   
															 motivo text
															);

create sequence plugins.prestacaocontascontabilidadefechamento_sequencial_seq; 