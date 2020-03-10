<%@ page import="java.security.*" %>
<%!
    public class CEncrypt
    {
        MessageDigest md;
        String strSRCData = "";
        String strENCData = "";

        public CEncrypt(){}
        //�ν��Ͻ� ���� �� �ѹ濡 ó���� �� �ֵ��� ������ �ߺ����׽��ϴ�. 
        public CEncrypt(String EncMthd, String strData)
        {
            this.encrypt(EncMthd, strData);
        }

        //��ȣȭ ������ �����ϴ� �޼ҵ��Դϴ�.
        public void encrypt(String EncMthd, String strData)
       {
           try
          {
              md = MessageDigest.getInstance(EncMthd); // "MD5" or "SHA1"
             byte[] bytData = strData.getBytes();
             md.update(bytData);

             byte[] digest = md.digest();
             for(int i =0;i<digest.length;i++)
             {
                 strENCData = strENCData + Integer.toHexString(digest[i] & 0xFF).toUpperCase();
             }
           }catch(NoSuchAlgorithmException e)
          {
             System.out.print("��ȣȭ �˰����� �����ϴ�.");
          };
        
          //���߿� ���� �����Ͱ� �ʿ����� ���� ������ �Ӵϴ�.
          strSRCData = strData;
        }

        //������ �ζ��� �Լ�(�ƴ�, �޼ҵ�)���Դϴ�.
        public String getEncryptData(){return strENCData;}
        public String getSourceData(){return strSRCData;}

        //�����Ͱ� ������ �����ִ� �޼ҵ��Դϴ�.
        public boolean equal(String strData)
        {
          //��ȣȭ �����Ͷ� �񱳸� �ϴ�, �����̶� �񱳸� �ϴ� �����....
          if(strData.equals(strENCData)) return true;
          return false;
        }
    }    //CEncrypt

/*
����� �̷��� �ϸ� �˴ϴ�.

    CEncrypt encrypt = new CEncrypt("MD5", "abcd");
    out.print(encrypt.getEncryptData());

    //�Ǵ�, �̷���
    CEncrypt encrypt = new CEncrypt("SHA1", "abcd");
    out.print(encrypt.getEncryptData());

    //�񱳴� �̷���...
    if(encrypt.equal("abcd")) out.print("ok;");
    else out.print("not ok;");
    */
%>