import java.sql.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Scanner;
public class Main {
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);
        System.out.print("Enter DB name: ");
        String db = sc.next();
        System.out.print("Enter username: ");
        String username=sc.next();
        System.out.print("Enter password: ");
        String password=sc.next();
        connectDB(db, username, password);
    }
    public static void connectDB(String db, String username, String password){
        try
        {
            //(1) PREPARATION
            //Connect to the computer's local database
            Class.forName("com.mysql.cj.jdbc.Driver");
            Connection myConn=DriverManager.getConnection("jdbc:mysql://localhost:3306/"+db,username,password);
            System.out.println("Successfully connected to database!");
            //Create the tables that are absent from the database
            //If the database has already contained all the necessary tables. No table will be created.
            Statement stmt=myConn.createStatement();
            ResultSet rs=stmt.executeQuery("show tables;");
            String [] tables = {"customers","employees","logs","products","purchases","suppliers"};
            HashMap<String, String> createTableQuery = new HashMap<String, String>();
            createTableQuery.put("employees","create table employees (eid varchar(3) not null, ename varchar(15),city varchar(15), primary key(eid));");
            createTableQuery.put("customers","create table customers (cid varchar(4) not null, cname varchar(15), city varchar(15), visits_made int(5), " +
                    "last_visit_time datetime, primary key(cid));");
            createTableQuery.put("suppliers","create table suppliers (sid varchar(2) not null, sname varchar(15) not null, city varchar(15), " +
                    "telephone_no char(10), primary key(sid), unique(sname));");
            createTableQuery.put("products","create table products (pid varchar(4) not null, pname varchar(15) not null, qoh int(5) not null, " +
                    "qoh_threshold int(5), original_price decimal(6,2), discnt_rate decimal(3,2), sid varchar(2), " +
                    "primary key(pid), foreign key (sid) references suppliers (sid));");
            createTableQuery.put("purchases","create table purchases (pur int not null, cid varchar(4) not null, eid varchar(3) not null, " +
                    "pid varchar(4) not null, qty int(5), ptime datetime, total_price decimal(7,2), " +
                    "primary key (pur), foreign key (cid) references customers(cid), foreign key (eid) references employees(eid), " +
                    "foreign key (pid) references products(pid)); ");
            createTableQuery.put("logs","create table logs (logid int(5) not null auto_increment, who varchar(10) not null, time datetime not null, " +
                    "table_name varchar(20) not null, operation varchar(6) not null, key_value varchar(4), primary key (logid));");
            ArrayList<String> tablesInDB = new ArrayList<>();
            while(rs.next()) {
                tablesInDB.add(rs.getString("tables_in_"+db));
            }
            for (int i=0;i<tables.length;i++){
                boolean exist=false;
                if(tablesInDB.size()>0){
                    for(int j=0;j<tablesInDB.size();j++){
                        if(tables[i].equals(tablesInDB.get(j))){
                            exist=true;
                            break;
                        }
                    }
                }
                if(exist==false){
                    Statement createTable = myConn.createStatement();
                    createTable.executeUpdate(createTableQuery.get(tables[i]));
                    System.out.println("Created table "+tables[i]);
                }
            }

            // (2) MYSQL IMPLEMENTATION
            //Create or replace existing functions
            stmt.executeUpdate("drop procedure if exists show_employees;");
            stmt.executeUpdate("drop procedure if exists show_customers;");
            stmt.executeUpdate("drop procedure if exists show_suppliers;");
            stmt.executeUpdate("drop procedure if exists show_products;");
            stmt.executeUpdate("drop procedure if exists show_purchases;");
            stmt.executeUpdate("drop procedure if exists show_logs;");
            stmt.executeUpdate("drop procedure if exists report_monthly_sale;");
            stmt.executeUpdate("drop procedure if exists add_purchase;");
            stmt.executeUpdate("drop procedure if exists add_product;");
            stmt.executeUpdate("drop trigger if exists trigger_update_products;");
            stmt.executeUpdate("drop trigger if exists trigger_update_customer;");
            stmt.executeUpdate("drop trigger if exists trigger_insert_purchases;");
            //Task 1
            stmt.executeUpdate("create procedure show_employees() "+"begin "+"select * from employees; "+"end");
            stmt.executeUpdate("create procedure show_customers() "+"begin "+"select * from customers; "+"end");
            stmt.executeUpdate("create procedure show_suppliers() "+"begin "+"select * from suppliers; "+"end");
            stmt.executeUpdate("create procedure show_products() "+"begin "+"select * from products; "+"end");
            stmt.executeUpdate("create procedure show_purchases() "+"begin "+"select * from purchases; "+"end");
            stmt.executeUpdate("create procedure show_logs() "+"begin "+"select * from logs; "+"end");
            //Task 2
            stmt.executeUpdate("create procedure report_monthly_sale(pid varchar(4)) "+"begin "+"select pname, date_format(ptime,'%b'),"
                    +" year(ptime),sum(qty),sum(total_price),sum(total_price)/sum(qty) from purchases pur, products pro "
                    +"where pur.pid=pid and pur.pid=pro.pid group by year(ptime), month(ptime); "+"end");
            //Task 3&5
            stmt.executeUpdate("create procedure add_purchase(pur_no int,c_id varchar(4),e_id varchar(3),p_id varchar(4),pur_qty int(5))" +
                    " add_purchase:" +
                    " begin" +

                    " declare price decimal default 0;" +
                    " declare res int default 0;" +
                    " declare msg varchar(500) default 'Query fail';" +

                    //Task 7
                    //7.1 detect whether the customer exists
                    " select count(cid) from customers where cid=c_id into res;" +
                    " if res<1 then" +
                    " set msg = 'Customer not found!';" +
                    " set @db_msg = 'Customer not found!';" +
                    " select res,msg;" +
                    " leave add_purchase;" +
                    " end if;" +
                    //7.2 detect whether the product exist
                    " select count(pid) from products where pid=p_id into res;" +
                    " if res<1 then" +
                    " set msg = 'Product not found!';" +
                    " set @db_msg = 'Product not found!';" +
                    " select res,msg;" +
                    " leave add_purchase;" +
                    " end if;" +
                    //7.3 detect whether the employee exist
                    " select count(eid) from employees where eid=e_id into res;" +
                    " if res<1 then" +
                    " set msg = 'Employee not found!';" +
                    " set @db_msg = 'Employee not found!';" +
                    " select res,msg;" +
                    " leave add_purchase;" +
                    " end if;" +

                    //Task 5
                    " select qoh from products where pid=p_id into res;" +
                    " if res<=pur_qty then" +
                    " set msg = 'Insufficient quantity in stock!';" +
                    " set @db_msg = 'Insufficient quantity in stock!';" +
                    " set res=0;"+
                    " select res, msg;" +
                    " leave add_purchase;" +
                    " end if;" +

                    " update products set qoh=qoh-pur_qty where pid=p_id;" +
                    " select count(*) from products where pid=p_id and qoh<qoh_threshold into res;" +
                    " if res>0 then" +
                    " set msg = 'The current qoh is: ';" +
                    //" select msg, qoh from products where pid=p_id into @p;" +
                    " update customers set visits_made=visits_made+1, last_visit_time=now() where cid=new.cid;" +
                    " update products set qoh=2*(qoh+pur_qty) where pid=p_id;" +
                    " select qoh/2+pur_qty from products where pid=p_id into @qoh_incr;" +
                    " set msg = 'The qoh is increased by: ';" +
                    " set @db_msg = concat('The qoh is increased by: ',@qoh_incr);" +

                    //" select msg, qoh+2*pur_qty from products where pid=p_id into @t;" +
                    " end if;" +

                    " insert into purchases (pur, cid, eid, pid, qty, ptime, total_price)" +
                    " select pur_no, c_id, e_id, p_id, pur_qty, current_timestamp, original_price*(1-discnt_rate)*pur_qty from products where pid=p_id;" +
                    " set res=1;" +
                    " set msg='added successfully';" +
                    " select res, msg;"+
                    "end");
            //Test: add_purchase(6,'0001','03','5292',2) (Add '' to varchar input to guarantee the desired format, otherwise it still works)
            stmt.executeUpdate("create procedure add_product(p_id varchar(4),p_name varchar(15),qoh int(5),qoh_threshold int(5),original_price decimal(6,2),discnt_rate decimal(3,2),s_id varchar(2))" +
                    " begin " +
                    " declare res int default 0;" +
                    " declare msg varchar(500) default 'Query fail';" +
                    " insert into products (pid, pname, qoh, qoh_threshold, original_price, discnt_rate, sid)" +
                    " values(p_id,p_name,qoh,qoh_threshold,original_price,discnt_rate,s_id);"+
                    " set res=1;" +
                    " set msg='added successfully';" +
                    " select res, msg;"+
                    " end");
            //Test: add_product('4656','Milk',20,60,2,0.08,'04')

            //Task 4
            //trigger for log product
            stmt.execute("create trigger trigger_update_products after update on products for each row" +
                    " begin" +
                    " insert into logs(who, time, table_name, operation, key_value)" +
                    " values(user(), now(), 'products', 'update', new.pid);" +
                    " end");
            //trigger for log customer
            stmt.execute("create trigger trigger_update_customer after update on customers for each row" +
                    " begin" +
                    " insert into logs(who, time, table_name, operation, key_value)" +
                    " values(user(), now(), 'customers', 'update', new.cid);" +
                    " end");


            //Task 6
            //trigger for log purchase of Task 4 and update purchase of Task 6
            stmt.execute(" create trigger trigger_insert_purchases after insert on purchases for each row" +
                    " begin" +
                    " declare res int default 0;" +
                    " declare msg varchar(500) default 'query fail';" +
                    " insert into logs (who, time, table_name, operation, key_value)" +
                    " values(user(), now(), 'purchases', 'insert', new.pur);" +
                    //" update products set qoh=qoh-new.qty where pid=new.pid;" +

                    "end");


            Scanner sc = new Scanner(System.in);
            String query;
            do{
                System.out.println("Enter your desired procedure (type \"exit\" to quit): ");
                query = sc.nextLine();
                try {
                    if (query.startsWith("show") || query.startsWith("report")) {
                        ResultSet resultSet = stmt.executeQuery("call " + query + ";");
                        ResultSetMetaData rsmd = resultSet.getMetaData();
                        int columnsNumber = rsmd.getColumnCount();
                        while (resultSet.next()) {
                            for (int i = 1; i <= columnsNumber; i++) {
                                System.out.print(resultSet.getString(i) + " ");
                            }
                            System.out.println();
                        }
                    } else if (query.startsWith("add")) {
                        stmt.executeUpdate("call " + query + ";");
                        ResultSet msg = stmt.executeQuery("select @db_msg");
                        while(msg.next()){
                            if(msg.getString(1)!=null){
                                System.out.println(msg.getString(1));
                            }
                        }
                        stmt.executeUpdate("set @db_msg=NULL;");
                        System.out.println("Procedure successfully executed.");
                    } else if (!query.equals("exit")){
                        System.out.println("Something wrong with your procedure. Please try again.");
                    }
                } catch (SQLException e){
                    System.out.println(e);
                    System.out.println("Something wrong with your procedure. Please try again.");
                }
            }while(!query.equals("exit"));
            System.out.println("Database disconnected.");
        }
        catch(SQLException e)
        {
            System.out.println(e);
        }
        catch(Exception e)
        {
            System.out.println(e);
        }
    }

}